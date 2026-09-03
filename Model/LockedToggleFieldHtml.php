<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model;

use Magefan\Community\Api\SecureHtmlRendererInterface;

/**
 * Renders the "locked" stand-in for a toggle-switch field gated behind a paid plan:
 * the same toggle + "Use Default Value" markup the real, working field would use,
 * both inert, with a transparent overlay on top that shows the upgrade popup
 * (Magefan_Translation/js/mf-upgrade-plan-popup) on any click.
 *
 * Shared between this module's own basic-tier teasers and TranslationPlus's fallback
 * for a feature Plus alone doesn't unlock (e.g. review auto-translation exclusion,
 * which only Magefan_TranslationExtra's observer actually acts on) - TranslationPlus
 * already depends on this module (see its Model\Config, which extends this module's),
 * so reusing this class avoids keeping the same markup in sync by hand across repos.
 */
class LockedToggleFieldHtml
{
    /**
     * @var SecureHtmlRendererInterface
     */
    private $mfSecureRenderer;

    /**
     * @param SecureHtmlRendererInterface $mfSecureRenderer
     */
    public function __construct(SecureHtmlRendererInterface $mfSecureRenderer)
    {
        $this->mfSecureRenderer = $mfSecureRenderer;
    }

    /**
     * @param string $plan Plan name shown in the popup, e.g. "Extra" or "Plus or Extra"
     * @param string $utmMedium
     * @param string $utmCampaign
     * @return string
     */
    public function render(string $plan, string $utmMedium, string $utmCampaign): string
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
            . $this->getClickHandlerHtml($plan, $utmMedium, $utmCampaign);
    }

    /**
     * @param \Magento\Framework\Phrase $phrase
     * @return string
     */
    private function escapeAttr($phrase): string
    {
        return htmlspecialchars((string)$phrase, ENT_QUOTES);
    }

    /**
     * Delegated on document rather than an inline onclick attribute - inline handlers
     * are blocked under a strict CSP.
     *
     * @param string $plan
     * @param string $utmMedium
     * @param string $utmCampaign
     * @return string
     */
    private function getClickHandlerHtml(string $plan, string $utmMedium, string $utmCampaign): string
    {
        $script = "document.addEventListener('click', function (event) {"
            . "if (!event.target.className || event.target.className.indexOf('mf-locked-toggle-overlay') === -1) {"
            . " return; }"
            . "require(['Magefan_Translation/js/mf-upgrade-plan-popup'], function (mfPopup) {"
            . " mfPopup(" . json_encode($plan) . ", " . json_encode($utmMedium) . ", " . json_encode($utmCampaign) . ");"
            . "});"
            . "});";

        return $this->mfSecureRenderer->renderTag('script', [], $script, false);
    }
}
