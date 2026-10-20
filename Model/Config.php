<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model;

use Magefan\Translation\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory as ConfigCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Filesystem\DirectoryList;

class Config implements ConfigInterface
{
    /**
     * Extension enabled config path
     */
    public const XML_PATH_EXTENSION_ENABLED = 'mftranslation/general/enabled';

    public const XML_PATH_USAGE_LIMITS_LIMIT_BY = 'mftranslation/mftranslationapis/usage_limits/limit_by';
    public const XML_PATH_USAGE_LIMITS_COUNT = 'mftranslation/mftranslationapis/usage_limits/count';

    public const XML_PATH_API_USAGE = 'mftranslation/today_api_usage';

    public const XML_PATH_AUTO_TRANSLATION_SKIPPED_FIELDS = 'mftranslation/auto_translation/skipped_fields';

    /**
     * credentials section
     */
    public const XML_PATH_GOOGLE_V3_CONFIG_FILE_NAME =
        'mftranslation/mftranslationapis/mfgoogleapi/v3_config_file_name';
    public const XML_PATH_GOOGLE_V3_AUTH_DATA = 'mftranslation/mftranslationapis/mfgoogleapi/v3_auth_data';

    public const SHOW_TO_CUSTOMER_ERROR_CODE = 911;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var ConfigCollectionFactory
     */
    protected $configCollectionFactory;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * Initialize dependencies.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param WriterInterface $configWriter
     * @param ConfigCollectionFactory $configCollectionFactory
     * @param DateTime $dateTime
     * @param EncryptorInterface $encryptor
     * @param DirectoryList $directoryList
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        WriterInterface $configWriter,
        ConfigCollectionFactory $configCollectionFactory,
        DateTime $dateTime,
        EncryptorInterface $encryptor,
        DirectoryList $directoryList
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
        $this->configCollectionFactory = $configCollectionFactory;
        $this->dateTime = $dateTime;
        $this->encryptor = $encryptor;
        $this->directoryList = $directoryList;
    }

    /**
     * Is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled(?int $storeId = null): bool
    {
        return (bool)$this->getConfig(self::XML_PATH_EXTENSION_ENABLED, $storeId);
    }

    /**
     * Get skipped fields.
     *
     * @param int|null $storeId
     * @return array
     */
    public function getSkippedFields(?int $storeId = null): array
    {
        return (array)json_decode(
            (string)$this->getConfig(self::XML_PATH_AUTO_TRANSLATION_SKIPPED_FIELDS, $storeId),
            true
        );
    }

    /**
     * Get usage limit by.
     *
     * @param int|null $storeId
     * @return int
     */
    public function getUsageLimitBy(?int $storeId = null): int
    {
        return (int)$this->getConfig(self::XML_PATH_USAGE_LIMITS_LIMIT_BY, $storeId);
    }

    /**
     * Get usage limit count.
     *
     * @param int|null $storeId
     * @return int
     */
    public function getUsageLimitCount(?int $storeId = null): int
    {
        return (int)$this->getConfig(self::XML_PATH_USAGE_LIMITS_COUNT, $storeId);
    }

    /**
     * Set today usage.
     *
     * @param int $count
     * @return void
     */
    public function setTodayUsage(int $count): void
    {
        $data = [
            $this->dateTime->date('Y_m_d') => $count
        ];

        $this->configWriter->save(self::XML_PATH_API_USAGE, json_encode($data));
    }

    /**
     * Get today usage.
     *
     * @return int
     */
    public function getTodayUsage(): int
    {
        $configValue = (string)$this->configCollectionFactory
            ->create()
            ->addFieldToFilter('path', ['eq' => self::XML_PATH_API_USAGE])
            ->getFirstItem()
            ->getValue();

        $data = json_decode($configValue, true);

        $todayUsage = $data[$this->dateTime->date('Y_m_d')] ?? 0;

        return (int)$todayUsage;
    }

    /**
     * Get encrypted auth config.
     *
     * @param string $scope
     * @param int|string|null $scopeId
     * @return string
     */
    public function getEncryptedAuthConfig($scope = ScopeInterface::SCOPE_WEBSITES, $scopeId = null): string
    {
        $encryptedConfig = $this->scopeConfig->getValue(
            self::XML_PATH_GOOGLE_V3_AUTH_DATA,
            $scope,
            $scopeId
        );

        return $this->encryptor->decrypt($encryptedConfig) ?: '';
    }

    /**
     * Get config file name.
     *
     * @param string $scope
     * @param int|string|null $scopeId
     * @return string
     */
    public function getConfigFileName($scope = ScopeInterface::SCOPE_WEBSITES, $scopeId = null): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GOOGLE_V3_CONFIG_FILE_NAME,
            $scope,
            $scopeId
        ) ?: '';
    }

    /**
     * Get config.
     *
     * @param string $path
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfig(string $path, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
