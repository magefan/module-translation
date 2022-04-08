<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Translation\Source;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

class StoreView extends Column
{
    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * System store
     *
     * @var SystemStore
     */
    protected $systemStore;

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
     * StoreView constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param SystemStore $systemStore
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     * @param string $storeKey
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SystemStore $systemStore,
        Escaper $escaper,
        array $components = [],
        array $data = [],
        $storeKey = 'store_id'
    ) {
        $this->systemStore = $systemStore;
        $this->escaper = $escaper;
        $this->storeKey = $storeKey;
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
     * @return \Magento\Framework\Phrase|string
     */
    protected function prepareItem(array $item)
    {
        $content = '';
        if (!empty($item[$this->storeKey])) {
            $origStores = $item[$this->storeKey];
        }

        if (empty($origStores)) {
            return 'All Store Views';
        }
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }
        if (in_array(0, $origStores) && count($origStores) == 1) {
            return __('All Store Views');
        }
        if (in_array(1, $origStores) && count($origStores) == 1) {
            return __('Default Store View');
        }
        $data = $this->systemStore->getStoresStructure(false, $origStores);

        foreach ($origStores as $store) {
            foreach ($data as $website) {
                //if ((int)$website['value'] == (int)$store) {
                    $content .= $website['label'] ."<br/>";
                //}
                foreach ($website['children'] as $group) {
                    //if ((int)$group['value'] == (int)$store) {
                        $content .= $this->escaper->escapeHtml($group['label']) ."<br/>";
                    //}
                    foreach ($group['children'] as $sto) {
                        if ((int)$sto['value'] == (int)$store) {
                            $content .= $this->escaper->escapeHtml($sto['label']) ."<br/>";
                        }
                    }
                }
            }
        }
        return $content;
    }
}
