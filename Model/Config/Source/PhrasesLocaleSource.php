<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class PhrasesLocaleSource extends \Magefan\Translation\Model\Config\Source\LocaleSource
{
    /**
     * Always includes en_US because Magento i18n phrases are in English by default,
     * even when no English store view is configured.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = array_filter(
            parent::toOptionArray(),
            static function ($option) { return $option['value'] !== 'en_US'; }
        );

        array_splice($options, 1, 0, [['value' => 'en_US', 'label' => 'English (United States)']]);

        return array_values($options);
    }
}
