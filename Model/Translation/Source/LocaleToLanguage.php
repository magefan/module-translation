<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Translation\Source;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Config\Model\Config\Source\Locale;

class LocaleToLanguage extends Column
{
    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Store manager
     *
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var string
     */
    protected $storeKey;

    /**
     * @var \Magento\Config\Model\Config\Source\Locale
     */
    public $locale;

    /**
     * LocaleToLanguage constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Escaper $escaper
     * @param Locale $locale
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Escaper $escaper,
        Locale $locale,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        $this->locale = $locale;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * @param array $item
     * @return string
     */
    protected function prepareItem(array $item)
    {
        $languageName = "";
        $locale = $this->locale->toOptionArray();
        foreach ($locale as $key => $value) {
            if ($item['locale'] ==  $value['value']) {
                $languageName =  $value['label'];
            }
        }

        return $languageName;
    }
}
