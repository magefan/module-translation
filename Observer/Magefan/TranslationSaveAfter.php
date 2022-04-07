<?php

/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Observer\Magefan;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Store\Model\StoreManagerInterface;

class TranslationSaveAfter implements ObserverInterface
{
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @var ThemeProviderInterface
     */
    private $themeProvider;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param DirectoryList $directoryList
     * @param File $driverFile
     * @param ScopeConfigInterface $scopeConfig
     * @param ThemeProviderInterface $themeProvider
     * @param Json $json
     * @param DateTime $date
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        DirectoryList $directoryList,
        File $driverFile,
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        Json $json,
        DateTime $date,
        StoreManagerInterface $storeManager
    ) {
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        $this->scopeConfig = $scopeConfig;
        $this->themeProvider = $themeProvider;
        $this->json = $json;
        $this->date = $date;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $storeId
     * @return string
     */
    private function getThemePathByStoreId(int $storeId, string $basePath, string $localCode): string
    {
        $themeId  = (int)$this->scopeConfig->getValue('design/theme/theme_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        $theme = $this->themeProvider->getThemeById($themeId);
        $themeFullPath = $theme->getFullPath();
        if (!($themeFullPath)) {
            return '';
        }
        return $basePath . '/' . $themeFullPath . '/' . $localCode . '/js-translation.json';
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $basePath =    $this->directoryList->getPath('static');
        $translation = $observer->getTranslation();
        $localCode = (string)$translation->getLocale();
        $storeId = (int)$translation->getStoreId();
        $original = $translation->getString();
        $tranlate = $translation->getTranslate();
        $paths = [];
        if ($storeId) {
            $paths[]=$this->getThemePathByStoreId($storeId, $basePath, $localCode);
        } else {
            $paths[] = $basePath . '/adminhtml/Magento/backend/' . $localCode . '/js-translation.json';
            foreach ($this->storeManager->getStores() as $store) {
                $path = $this->getThemePathByStoreId((int)$store->getId(), $basePath, $localCode);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }
        if (!empty($paths)) {
            $changed=false;
            $paths = array_unique($paths);
            foreach ($paths as $path) {
                $exists = $this->driverFile->isExists($path);
                if ($exists) {
                    $fileContent = $this->driverFile->fileGetContents($path);
                    if ($fileContent) {
                        try {
                            $jsonDecoded = $this->json->unserialize($fileContent);
                        } catch (\InvalidArgumentException $e) {
                            $jsonDecoded = [];
                        }
                        if (isset($jsonDecoded[$original])) {
                            $jsonDecoded[$original] = $tranlate;
                            $jsonEncoded = $this->json->serialize($jsonDecoded);
                            $this->driverFile->filePutContents($path, $jsonEncoded);
                            $changed = true;
                        }
                    }
                }
            }
            if ($changed) {
                $timestampNew = (string)$this->date->gmtTimestamp($this->date->gmtDate());
                $this->driverFile->filePutContents($basePath . '/deployed_version.txt', $timestampNew);
            }
        }
    }
}
