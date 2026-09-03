<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit;

use Magefan\Community\Api\SecureHtmlRendererInterface;
use Magefan\Translation\Model\Config;

/**
 * Basic-tier teaser for the "Exclude From Auto Translation" review field. Registered
 * under the same plugin name ("magefan_translation_plus_review_edit_form_use_default")
 * as Magefan\TranslationPlus\Plugin\Magento\Review\Block\Adminhtml\Edit\FormPlugin on
 * the same target class - TranslationPlus loads after Translation, so its plugin
 * definition overrides this one entirely (the real, working fieldset) when Plus is
 * installed.
 *
 * Mirrors Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit (the
 * "Auto Translate" button teaser) and the locked "Auto Translation (Extra)" fieldset
 * category/product forms already show (Magefan_Translation/js/form/components/locked-fieldset) -
 * this is the same idea, hand-built, because the review edit form is a legacy
 * Widget\Form\Generic, not a UI component.
 */
class FormPlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var SecureHtmlRendererInterface
     */
    private $mfSecureRenderer;

    /**
     * @param Config $config
     * @param SecureHtmlRendererInterface $mfSecureRenderer
     */
    public function __construct(
        Config $config,
        SecureHtmlRendererInterface $mfSecureRenderer
    ) {
        $this->config = $config;
        $this->mfSecureRenderer = $mfSecureRenderer;
    }

    /**
     * @param \Magento\Review\Block\Adminhtml\Edit\Form $subject
     * @param \Magento\Framework\Data\Form|null $form
     * @return \Magento\Framework\Data\Form|null
     */
    public function afterGetForm($subject, $form)
    {
        if (!$form || !$this->config->isEnabled()) {
            return $form;
        }

        // getForm() runs more than once per page render - without this guard the
        // fieldset and its click handler script would be emitted multiple times.
        if (!$form->getElement('review_details') || $form->getElement('mf_auto_translation')) {
            return $form;
        }

        $fieldset = $form->addFieldset(
            'mf_auto_translation',
            ['legend' => __('Auto Translation (Extra)')],
            'review_details'
        );

        $fieldset->addField(
            'mf_exclude_auto_translation',
            'note',
            [
                'label' => __('Exclude From Auto Translation'),
                'text' => $this->getLockedContentHtml(),
            ]
        );

        return $form;
    }

    /**
     * The toggle and its "Use Default Value" checkbox are both left disconnected from
     * any submitted field name - there is nothing to persist on this tier - and the
     * transparent overlay drawn on top intercepts every click on either of them.
     *
     * @return string
     */
    private function getLockedContentHtml()
    {
        return '<div style="position:relative;display:inline-block;">'
            . '<div>'
            . '<div class="admin__actions-switch" data-role="switcher">'
            . '<input type="checkbox" class="admin__actions-switch-checkbox" id="mf_exclude_auto_translation_teaser" disabled="disabled" />'
            . '<label class="admin__actions-switch-label" for="mf_exclude_auto_translation_teaser">'
            . '<span class="admin__actions-switch-text" data-text-on="' . $this->escapeAttr(__('Yes'))
            . '" data-text-off="' . $this->escapeAttr(__('No')) . '"></span>'
            . '</label>'
            . '</div>'
            . '<label style="display:block;margin-top:8px;font-weight:normal;">'
            . '<input type="checkbox" class="checkbox" checked="checked" disabled="disabled" /> '
            . $this->escapeAttr(__('Use Default Value'))
            . '</label>'
            . '</div>'
            . '<div class="mf-auto-translation-lock-overlay"'
            . ' style="position:absolute;top:0;left:0;right:0;bottom:0;cursor:pointer;z-index:1;"></div>'
            . '</div>'
            . $this->getClickHandlerHtml();
    }

    /**
     * @param \Magento\Framework\Phrase $phrase
     * @return string
     */
    private function escapeAttr($phrase)
    {
        return htmlspecialchars((string)$phrase, ENT_QUOTES);
    }

    /**
     * Delegated on document rather than an inline onclick attribute, same as
     * Magefan\TranslationPlus\Plugin\Magento\Review\Block\Adminhtml\Edit\FormPlugin::getToggleScriptHtml() -
     * inline handlers are blocked under a strict CSP.
     *
     * @return string
     */
    private function getClickHandlerHtml()
    {
        $script = "document.addEventListener('click', function (event) {"
            . "if (!event.target.className || event.target.className.indexOf('mf-auto-translation-lock-overlay') === -1) {"
            . " return; }"
            . "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function (mfPopup) {"
            . " mfPopup('Extra', 'review-edit', 'fieldset');"
            . "});"
            . "});";

        return $this->mfSecureRenderer->renderTag('script', [], $script, false);
    }
}
