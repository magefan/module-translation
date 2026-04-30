<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magefan\Community\Block\Adminhtml\Edit\GenericButton;

class AutoTranslateButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Auto Translate'),
            'class' => 'mf_auto_translate',
            'on_click' => "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function(mfPopup) {"
                . "mfPopup('Extra', 'auto-translate', 'button');"
                . "});",
            'sort_order' => 20,
        ];
    }
}
