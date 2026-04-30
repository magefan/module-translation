<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class LocaleSource extends \Magefan\Translation\Model\Config\Source\Locale
{
    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        $result = parent::toOptionArray();
        array_unshift(
            $result,
            ['value' => '', 'label' => __('Use Default Store Locale')]
        );

        return $result;
    }
}
