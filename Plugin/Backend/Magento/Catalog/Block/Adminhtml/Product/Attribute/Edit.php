<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Catalog\Block\Adminhtml\Product\Attribute;

use Magefan\Translation\Model\Config;

class Edit
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    public function beforeSetLayout(
        \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit $subject,
        $layout
    ) {
        if ($this->config->isEnabled() && $subject->getRequest()->getParam('attribute_id')) {
            $subject->addButton(
                'mftranslation_locked_button',
                [
                    'label'    => __('Auto Translate'),
                    'on_click' => $this->getOnClick(),
                    'class'    => 'mf_auto_translate',
                ],
                10
            );
        }

        return [$layout];
    }

    private function getOnClick(): string
    {
        return "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function(mfPopup){"
            . "mfPopup('Extra', 'auto-translate', 'button');"
            . "});";
    }
}
