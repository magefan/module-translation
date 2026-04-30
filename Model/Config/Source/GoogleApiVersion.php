<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class GoogleApiVersion implements \Magento\Framework\Data\OptionSourceInterface
{
    public const VERSION_2 = 'v2';
    public const VERSION_3 = 'v3';

    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::VERSION_2, 'label' => __('V2 (Basic - API Key)')],
            ['value' => self::VERSION_3, 'label' => __('V3 (Advanced - JSON Key / Glossaries)')]
        ];
    }
}
