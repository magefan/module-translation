<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class DeepSeekModels implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'deepseek-v4-flash', 'label' => 'deepseek-v4-flash'],
            ['value' => 'deepseek-v4-pro', 'label' => 'deepseek-v4-pro']
        ];
    }
}
