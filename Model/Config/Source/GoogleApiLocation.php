<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class GoogleApiLocation implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'us-central1', 'label' => __('US Central (Iowa)')],
            ['value' => 'us-east1', 'label' => __('US East (South Carolina)')],
            ['value' => 'europe-west1', 'label' => __('Europe West (Belgium)')],
            ['value' => 'europe-west2', 'label' => __('Europe West (London)')],
            ['value' => 'europe-west3', 'label' => __('Europe West (Frankfurt)')],
            ['value' => 'europe-west4', 'label' => __('Europe West (Netherlands)')],
            ['value' => 'europe-central2', 'label' => __('Europe Central (Warsaw)')],
            ['value' => 'asia-east1', 'label' => __('Asia East (Taiwan)')],
            ['value' => 'asia-northeast1', 'label' => __('Asia Northeast (Tokyo)')],
            ['value' => 'australia-southeast1', 'label' => __('Australia Southeast (Sydney)')]
        ];
    }
}
