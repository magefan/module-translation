<?php

/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Observer\Backend\Magefan;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\DateTime;

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
     * @param DirectoryList $directoryList
     * @param File $driverFile
     * @param ScopeConfigInterface $scopeConfig
     * @param ThemeProviderInterface $themeProvider
     * @param Json $json
     * @param DateTime $date
     */
    public function __construct(
        DirectoryList $directoryList,
        File $driverFile,
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        Json $json,
        DateTime $date
    )
    {
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        $this->scopeConfig = $scopeConfig;
        $this->themeProvider = $themeProvider;
        $this->json = $json;
        $this->date = $date;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $basePath = $this->directoryList->getPath('static');
        $translation = $observer->getTranslation();
        $translationData = $translation->getData();
        $localCode = $translationData['locale'];
        $store_id = $translationData['store_id'];
        $original = $translationData['string'];
        $tranlated = $translationData['translate'];

        if($store_id){
            $design  =$this->scopeConfig->getValue('design', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store_id);
            $themeId = $design['theme']['theme_id'];
            $theme = $this->themeProvider->getThemeById($themeId);
            $themeFullPath = $theme->getFullPath();
            $path = $basePath . '/' . $themeFullPath . '/' . $localCode . '/js-translation.json';
        }
        else {
            $path = $basePath . '/adminhtml/Magento/backend/' . $localCode . '/js-translation.json';
        }

        if($path) {
            $exists = $this->driverFile->isExists($path);
            if ($exists) {
                $jsonDecode = $this->json->unserialize($this->driverFile->fileGetContents($path));
                if (isset($jsonDecode[$original])) {
                    $jsonDecode[$original] = $tranlated;
                    $jsonEncode = $this->json->serialize($jsonDecode);
                    $this->driverFile->filePutContents($path, $jsonEncode);
                    $timestampNew = (string)$this->date->gmtTimestamp($this->date->gmtDate());
                    $this->driverFile->filePutContents($basePath . '/deployed_version.txt', $timestampNew);
                }
            }
        }
    }
}