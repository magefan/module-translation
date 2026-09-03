<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml;

use Magefan\Translation\Model\Config;

/**
 * Basic-tier teaser for the review "Auto Translate" button. Registered under the same
 * plugin name as Magefan\TranslationExtra\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit
 * on the same target class - Extra loads after Translation, so its plugin definition
 * overrides this one (real button instead of the upgrade popup) when Extra is installed.
 */
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

    /**
     * @param \Magento\Review\Block\Adminhtml\Edit $subject
     * @param \Magento\Framework\View\Layout $layout
     * @return array
     */
    public function beforeSetLayout(
        \Magento\Review\Block\Adminhtml\Edit $subject,
        $layout
    ) {
        if ($this->config->isEnabled() && $subject->getRequest()->getParam('id')) {
            $subject->addButton(
                'mftranslation_locked_button',
                [
                    'label' => __('Auto Translate'),
                    'on_click' => $this->getOnClick(),
                    'class' => 'mf_auto_translate',
                ],
                10
            );
        }

        return [$layout];
    }

    /**
     * @return string
     */
    private function getOnClick(): string
    {
        return "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function(mfPopup){"
            . "mfPopup('Extra', 'auto-translate', 'button');"
            . "});";
    }
}
