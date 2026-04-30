<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class DeeplApiUrl implements \Magento\Framework\Option\ArrayInterface
{
    public const FREE_API_URL = 'https://api-free.deepl.com/v2/translate';
    public const PAID_API_URL = 'https://api.deepl.com/v2/translate';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::FREE_API_URL, 'label' => self::FREE_API_URL],
            ['value' => self::PAID_API_URL, 'label' => self::PAID_API_URL],
        ];
    }
}
