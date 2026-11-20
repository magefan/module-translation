<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class ClaudeModels implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'claude-opus-5-5', 'label' => 'Claude Opus 5.5'],
            ['value' => 'claude-sonnet-5-5', 'label' => 'Claude Sonnet 5.5'],
            ['value' => 'claude-haiku-5-5', 'label' => 'Claude Haiku 5.5'],
        ];
    }
}
