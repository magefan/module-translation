<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Backend\Block\Widget\Button;

use Magefan\Translation\Model\Config;

/**
 * Basic-tier teaser for the email template "Auto Translate" button.
 *
 * The email template edit block adds its buttons straight to its protected button list
 * in _prepareLayout() and hands them to the toolbar, so the toolbar is the only public
 * place to add one. Registered under the same plugin name as
 * Magefan\TranslationExtra\Plugin\Backend\Magento\Backend\Block\Widget\Button\EmailTemplateToolbar -
 * Extra loads after Translation, so its plugin replaces this one when installed.
 */
class EmailTemplateToolbar
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Initialize dependencies.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Add the locked "Auto Translate" button to the email template edit page.
     *
     * @param \Magento\Backend\Block\Widget\Button\ToolbarInterface $subject
     * @param \Magento\Framework\View\Element\AbstractBlock $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
     * @return array
     */
    public function beforePushButtons($subject, $context, $buttonList)
    {
        if ($context instanceof \Magento\Email\Block\Adminhtml\Template\Edit
            && $this->config->isEnabled()
            && $context->getRequest()->getParam('id')
        ) {
            $buttonList->add(
                'mftranslation_locked_button',
                [
                    'label' => __('Auto Translate'),
                    'onclick' => $this->getOnClick(),
                    'class' => 'mf_auto_translate',
                ],
                0,
                10
            );
        }

        return [$context, $buttonList];
    }

    /**
     * Get on click.
     *
     * @return string
     */
    private function getOnClick(): string
    {
        return "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function(mfPopup){"
            . "mfPopup('Extra', 'auto-translate', 'button');"
            . "});";
    }
}
