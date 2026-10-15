<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend;

use Magefan\Community\Api\SecureHtmlRendererInterface;
use Magefan\Translation\Model\Config;

/**
 * Base for the basic-tier "Exclude From Auto Translation" teasers on legacy admin edit
 * forms (Widget\Form\Generic, not UI components - e.g. review, email template).
 *
 * Mirrors the locked "Auto Translation (Extra)" fieldset category/product forms already
 * show (Magefan_Translation/js/form/components/locked-fieldset), hand-built, since there
 * is no ui_component form to declare it in.
 *
 * Each subclass is registered under the same plugin name as its
 * Magefan\TranslationPlus counterpart, which extends it and replaces it when Plus is
 * installed. Those reuse addAutoTranslationFieldset() and
 * addLockedExcludeAutoTranslationField() for their real field and for their Extra-less
 * fallback (Plus alone doesn't act on the flag), which is why they're protected.
 */
abstract class AbstractLegacyEditFormPlugin
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
     * Id of the core fieldset the "Auto Translation (Extra)" one is inserted after.
     *
     * @return string
     */
    abstract protected function getBaseFieldsetId(): string;

    /**
     * utm_medium of the upgrade popup, telling which page it was opened from.
     * A plain slug, it is put into the script as is.
     *
     * @return string
     */
    abstract protected function getUpgradePopupSource(): string;

    /**
     * Adds the "Auto Translation (Extra)" fieldset right after the base fieldset, or
     * returns null without touching the form if it's already there (getForm() runs
     * more than once per page render) or the base fieldset itself isn't present to
     * insert after.
     *
     * @param \Magento\Framework\Data\Form $form
     * @return \Magento\Framework\Data\Form\Element\Fieldset|null
     */
    protected function addAutoTranslationFieldset($form)
    {
        $baseFieldsetId = $this->getBaseFieldsetId();

        if (!$form->getElement($baseFieldsetId) || $form->getElement('mf_auto_translation')) {
            return null;
        }

        return $form->addFieldset(
            'mf_auto_translation',
            ['legend' => __('Auto Translation (Extra)')],
            $baseFieldsetId
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
     * @param \Magento\Framework\Phrase|string $phrase
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
            . " mfPopup('Extra', '" . $this->getUpgradePopupSource() . "', 'fieldset');"
            . "});"
            . "});";

        return $this->mfSecureRenderer->renderTag('script', [], $script, false);
    }
}
