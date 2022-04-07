<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

use Magento\Framework\Locale\Bundle\LanguageBundle;
use Magento\Framework\Locale\Bundle\RegionBundle;

class Locale implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var \Magento\Framework\Locale\ConfigInterface
     */
    protected $config;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\Locale\ConfigInterface $config
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver =  $localeResolver;
        $this->config = $config;
    }

    /**
     * Sort option array.
     *
     * @param array $option
     * @return array
     */
    protected function sortOptionArray($option)
    {
        $data = [];
        foreach ($option as $item) {
            $data[$item['value']] = $item['label'];
        }
        asort($data);
        $option = [];
        foreach ($data as $key => $label) {
            $option[] = ['value' => $key, 'label' => $label];
        }
        return $option;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $locales = [];
        foreach ($this->storeManager->getStores() as $store) {
            $locale = $this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getStoreId());
            if (!in_array($locale, $locales)) {
                $locales[] = $locale;
            }
        }
        $currentLocale = $this->localeResolver->getLocale();
        $languages = (new LanguageBundle())->get($currentLocale)['Languages'];
        $countries = (new RegionBundle())->get($currentLocale)['Countries'];
        $options = [];
        $allowedLocales = $this->config->getAllowedLocales();
        foreach ($locales as $locale) {
            if (!in_array($locale, $allowedLocales)) {
                continue;
            }
            $language = \Locale::getPrimaryLanguage($locale);
            $country = \Locale::getRegion($locale);
            $script = \Locale::getScript($locale);
            $scriptTranslated = '';
            if ($script !== '') {
                $script = \Locale::getDisplayScript($locale) . ', ';
                $scriptTranslated = \Locale::getDisplayScript($locale, $locale) . ', ';
            }

                $label = $languages[$language]
                    . ' (' . $script . $countries[$country] . ')';

            $options[] = ['value' => $locale, 'label' => $label];
        }
        return $this->sortOptionArray($options);
    }
}
