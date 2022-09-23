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
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* Moved to Magefan_TranslationPlus */
    }
}
