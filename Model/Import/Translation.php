<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Import;

use Magefan\Translation\Model\Import\Translation\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Store\Model\StoreManagerInterface;

class Translation extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    public const KEY_ID = 'key_id';

    public const STRING  = 'string';

    public const STORE_ID = 'store_id';

    public const TRANSLATE = 'translate';

    public const LOCALE = 'locale';

    public const TABLE_ENTITY = 'translation';

    public const CRC_STRING = 'crc_string';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'TITLE is empty',
    ];

    /**
     * @var array
     */
    protected $_permanentAttributes = [self::STRING];

    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;

    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * @var array
     */
    protected $validColumnNames = [
        self::KEY_ID,
        self::STRING,
        self::STORE_ID,
        self::TRANSLATE,
        self::LOCALE,
        self::CRC_STRING
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    /**
     * @var array
     */
    protected $_validators = [];

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var null
     */
    private $allStoreIds = null;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\ImportExport\Helper\Data $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        StoreManagerInterface $storeManager
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
    }
    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'translation';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $title = false;
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        // BEHAVIOR_DELETE use specific validation logic
        // if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
        if (!isset($rowData[self::STRING]) || empty($rowData[self::STRING])) {
            $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
            return false;
        }

        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE != $this->getBehavior()) {
            foreach ([self::STORE_ID, self::LOCALE, self::TRANSLATE] as $column) {
                // Empty values become NULL when saved, like in the admin form a value is required
                if (!isset($rowData[$column]) || $rowData[$column] === '') {
                    $errorMessage = (string) __('"%1" is required.', $column);
                    $this->addRowError(ValidatorInterface::ERROR_COLUMN_IS_EMPTY, $rowNum, $column, $errorMessage);
                    return false;
                }
            }
        }

        $storeId = $rowData[self::STORE_ID] ?? null;

        if (!in_array((string)$storeId, $this->getAllStoreIds(), true)) {
            $errorMessage = (string) __('Store with ID %1 does not exist.', $storeId);
            $this->addRowError(ValidatorInterface::ERROR_STORE_ID_NOT_EXIST, $rowNum, null, $errorMessage);
            return false;
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }
        return true;
    }

    /**
     * Save transl
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Replace translation
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Deletes translation data from raw data.
     *
     * Rows are matched by string, store view and locale; files without a locale column delete by string.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $conditions = [];
        $strings = [];
        while ($bunch = $this->getCurrentImportBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    if (isset($rowData[self::LOCALE]) && $rowData[self::LOCALE] !== '') {
                        $key = $this->getRowKey($rowData);
                        $conditions[$key] = $this->_connection->quoteInto('store_id = ?', (int)$rowData[self::STORE_ID])
                            . $this->_connection->quoteInto(' AND locale = ?', (string)$rowData[self::LOCALE])
                            . $this->_connection->quoteInto(' AND crc_string = ?', crc32($rowData[self::STRING]));
                    } else {
                        $strings[] = $rowData[self::STRING];
                    }
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($strings) {
            $conditions[] = $this->_connection->quoteInto('string IN (?)', array_unique($strings));
        }
        if ($conditions) {
            $this->deleteEntityFinish(array_values($conditions), self::TABLE_ENTITY);
        }
        return $this;
    }

    /**
     * Save and replace translation
     *
     * Replace removes the existing translations of each string in the locales of the file (all store views of that
     * locale) before saving the rows of the file; other locales stay.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        // Locale and string already replaced: a later bunch must not delete rows saved by an earlier one
        $replaced = [];
        while ($bunch = $this->getCurrentImportBunch()) {
            $entityList = [];
            $toReplace = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowTtile= $rowData[self::STRING];
                $crc = crc32($rowData[self::STRING]);
                $entityList[$rowTtile][] = [
                    self::KEY_ID    => $rowData[self::KEY_ID] ?? null,
                    self::STRING    => $rowData[self::STRING],
                    self::STORE_ID  => $rowData[self::STORE_ID],
                    self::TRANSLATE => $rowData[self::TRANSLATE],
                    self::LOCALE    => $rowData[self::LOCALE],
                    self::CRC_STRING => $crc
                ];
                $replaceKey = $rowData[self::LOCALE] . '|' . $crc;
                if (!isset($replaced[$replaceKey])) {
                    $replaced[$replaceKey] = true;
                    $toReplace[(string)$rowData[self::LOCALE]][] = $crc;
                }
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                $conditions = [];
                foreach ($toReplace as $locale => $crcs) {
                    $conditions[] = $this->_connection->quoteInto('locale = ?', $locale)
                        . $this->_connection->quoteInto(' AND crc_string IN (?)', $crcs);
                }
                if (!$conditions || $this->deleteEntityFinish($conditions, self::TABLE_ENTITY)) {
                    $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
            }
        }
        return $this;
    }

    /**
     * Save the imported translations.
     *
     * A translation is identified by store view, locale and string (the unique key of the table): key_id of the
     * file is not used, so a file from another environment cannot overwrite a different translation.
     *
     * @param array $entityData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {

            $entityData = new \Magento\Framework\DataObject(
                ['elements' => $entityData]
            );
            $this->eventManager->dispatch('magefan_translation_save_import_entity_before', [
                'entityData' => $entityData,
                'table' => $table
            ]);
            $entityData = $entityData->getData('elements');

            $tableName  = $this->_resource->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                foreach ($entityRows as $row) {
                    unset($row[self::KEY_ID]);
                    $row[self::CRC_STRING] = crc32($row[self::STRING]);
                    $entityIn[$this->getRowKey($row)] = $row;
                }
            }

            if ($entityIn) {
                $existing = [];
                $select = $this->_connection->select()
                    ->from($tableName, [self::STORE_ID, self::LOCALE, self::CRC_STRING])
                    ->where('crc_string IN (?)', array_unique(array_column($entityIn, self::CRC_STRING)));
                foreach ($this->_connection->fetchAll($select) as $row) {
                    $existing[$this->getRowKey($row)] = true;
                }
                foreach (array_keys($entityIn) as $key) {
                    $this->countItemsUpdated += (int)isset($existing[$key]);
                    $this->countItemsCreated += (int)!isset($existing[$key]);
                }

                $this->_connection->insertOnDuplicate($tableName, array_values($entityIn), [
                    self::STRING,
                    self::TRANSLATE,
                ]);
            }
        }
        return $this;
    }

    /**
     * Delete entity finish.
     *
     * @param array $conditions SQL conditions, a row matching any of them is deleted
     * @param string $table
     * @return bool
     */
    protected function deleteEntityFinish(array $conditions, $table)
    {
        if ($table && $conditions) {
            try {
                $tableName = $this->_resource->getTableName($table);
                foreach (array_chunk($conditions, 500) as $chunk) {
                    $this->countItemsDeleted += $this->_connection->delete(
                        $tableName,
                        '(' . implode(') OR (', $chunk) . ')'
                    );
                }
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Unique key of a translation row: store view, locale and string CRC
     *
     * @param array $row
     * @return string
     */
    private function getRowKey(array $row): string
    {
        $crc = isset($row[self::CRC_STRING]) ? (int)$row[self::CRC_STRING] : crc32((string)$row[self::STRING]);

        return (int)$row[self::STORE_ID] . '|' . $row[self::LOCALE] . '|' . $crc;
    }

    /**
     * Next bunch of this import only
     *
     * Magento 2.4.4+ keeps the bunches of other imports in importexport_importdata (e.g. files that were checked
     * but never imported), so getNextBunch() would import them again. Older versions clean the table on validation.
     *
     * @return array|null
     */
    private function getCurrentImportBunch()
    {
        if (method_exists($this, 'getIds') && method_exists($this->_dataSourceModel, 'getNextUniqueBunch')) {
            return $this->_dataSourceModel->getNextUniqueBunch($this->getIds());
        }

        return $this->_dataSourceModel->getNextBunch();
    }

    /**
     * Get all store ids.
     *
     * @return array
     */
    private function getAllStoreIds(): array
    {
        if (null === $this->allStoreIds) {
            $this->allStoreIds = [];

            foreach ($this->storeManager->getStores(true) as $store) {
                $this->allStoreIds[] = (string)$store->getId();
            }
        }
        return $this->allStoreIds;
    }
}
