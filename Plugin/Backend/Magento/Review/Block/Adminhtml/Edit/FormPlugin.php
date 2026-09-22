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
 * definition overrides this one entirely (the real, working fieldset) when Plus and
 * Extra are both installed.
 *
 * That subclass also reuses addAutoTranslationFieldset() and
 * addLockedExcludeAutoTranslationField() below for its own Extra-less fallback (Plus
 * alone doesn't act on this flag - see that class), which is why they're protected
 * rather than private.
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
    protected $config;

    /**
     * @var SecureHtmlRendererInterface
     */
    protected $mfSecureRenderer;

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

        $this->addLockedExcludeAutoTranslationField($form);

        return $form;
    }

    /**
     * Adds the "Auto Translation (Extra)" fieldset right after review_details, or
     * returns null without touching the form if it's already there (getForm() runs
     * more than once per page render) or review_details itself isn't present to
     * insert after.
     *
     * Shared between this class's own locked field below and
     * Magefan\TranslationPlus\...\FormPlugin's real one, so the guard only lives once.
     *
     * @param \Magento\Framework\Data\Form $form
     * @return \Magento\Framework\Data\Form\Element\Fieldset|null
     */
    protected function addAutoTranslationFieldset($form)
    {
        if (!$form->getElement('review_details') || $form->getElement('mf_auto_translation')) {
            return null;
        }

        return $form->addFieldset(
            'mf_auto_translation',
            ['legend' => __('Auto Translation (Extra)')],
            'review_details'
        );
    }

    /**
     * Adds the locked "Exclude From Auto Translation" field: the same toggle a working
     * install would show, disabled, with a click-anywhere overlay that opens the
     * upgrade popup instead of doing anything.
     *
     * @param \Magento\Framework\Data\Form $form
     * @return void
     */
    protected function addLockedExcludeAutoTranslationField($form)
    {
        $fieldset = $this->addAutoTranslationFieldset($form);

        if (!$fieldset) {
            return;
        }

        $fieldset->addField(
            'mf_exclude_auto_translation',
            'note',
            [
                'label' => __('Exclude From Auto Translation'),
                'text' => $this->getLockedToggleHtml(),
            ]
        );
    }

    /**
     * Renders a locked stand-in for a toggle-switch field: the same toggle +
     * "Use Default Value" markup the real, working field would use, both inert, with a
     * transparent overlay on top that shows the upgrade popup on any click.
     *
     * @return string
     */
    private function getLockedToggleHtml()
    {
        $yes = $this->escapeAttr(__('Yes'));
        $no = $this->escapeAttr(__('No'));

        return '<div style="position:relative;display:inline-block;">'
            . '<div>'
            . '<div class="admin__actions-switch" data-role="switcher">'
            . '<input type="checkbox" class="admin__actions-switch-checkbox" id="mf_locked_toggle" disabled="disabled" />'
            . '<label class="admin__actions-switch-label" for="mf_locked_toggle">'
            . '<span class="admin__actions-switch-text" data-text-on="' . $yes . '" data-text-off="' . $no . '"></span>'
            . '</label>'
            . '</div>'
            . '<label style="display:block;margin-top:8px;font-weight:normal;">'
            . '<input type="checkbox" class="checkbox" checked="checked" disabled="disabled" /> '
            . $this->escapeAttr(__('Use Default Value'))
            . '</label>'
            . '</div>'
            . '<div class="mf-locked-toggle-overlay"'
            . ' style="position:absolute;top:0;left:0;right:0;bottom:0;cursor:pointer;z-index:1;"></div>'
            . '</div>'
            . $this->getLockedClickHandlerHtml();
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
     * Delegated on document rather than an inline onclick attribute - inline handlers
     * are blocked under a strict CSP. classList.contains() rather than a className
     * substring match: className is an SVGAnimatedString (no .indexOf) on an SVG
     * target, e.g. any of the admin icon sprites elsewhere on the page.
     *
     * @return string
     */
    private function getLockedClickHandlerHtml()
    {
        $script = "document.addEventListener('click', function (event) {"
            . "if (!event.target.classList || !event.target.classList.contains('mf-locked-toggle-overlay')) {"
            . " return; }"
            . "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function (mfPopup) {"
            . " mfPopup('Extra', 'review-edit', 'fieldset');"
            . "});"
            . "});";

        return $this->mfSecureRenderer->renderTag('script', [], $script, false);
    }
}
