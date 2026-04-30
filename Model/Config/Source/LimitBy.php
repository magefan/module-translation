<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class LimitBy implements ArrayInterface
{
    public const CHARACTERS = 1;
    public const REQUESTS = 2;

    public function toOptionArray()
    {
        return [
            ['value' => self::CHARACTERS, 'label' => __('Characters')],
            ['value' => self::REQUESTS, 'label' => __('Requests')]
        ];
    }
}
