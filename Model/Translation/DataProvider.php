<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Translation;

use Magefan\Translation\Model\ResourceModel\Translation\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magefan\Translation\Model\ResourceModel\Translation\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $translationCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $translationCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $translationCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $translation \Magefan\Translation\Model\Translation */
        foreach ($items as $translation) {
            $translation->setData(
                'mf_locale',
                $translation->getData('locale')
            );
            $translation->unsetData('locale');

            $this->loadedData[$translation->getId()] = $translation->getData();
        }

        $data = $this->dataPersistor->get('translation_translation');
        if (!empty($data)) {
            $translation = $this->collection->getNewEmptyItem();
            $translation->setData($data);
            $this->loadedData[$translation->getId()] = $translation->getData();
            $this->dataPersistor->clear('translation_translation');
        }

        return $this->loadedData;
    }
}
