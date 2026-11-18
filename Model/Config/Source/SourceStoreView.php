<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

use Magento\Config\Model\Config\Source\Store as StoreSource;
use Magento\Framework\Option\ArrayInterface;

class SourceStoreView implements ArrayInterface
{
    /**
     * @var StoreSource
     */
    private $storeSource;

    /**
     * @param StoreSource $storeSource
     */
    public function __construct(StoreSource $storeSource)
    {
        $this->storeSource = $storeSource;
    }

    /**
     * Store views with "All Store Views" as the first (default) option.
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        $options = $this->storeSource->toOptionArray();

        array_unshift(
            $options,
            ['value' => 0, 'label' => __('All Store Views (default)')]
        );

        return $options;
    }
}
