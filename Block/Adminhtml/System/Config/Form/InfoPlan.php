<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

use Magefan\Community\Api\SecureHtmlRendererInterface;

abstract class InfoPlan extends \Magefan\Community\Block\Adminhtml\System\Config\Form\Info
{
    /**
     * @return string
     */
    abstract protected function getMinPlan(): string;

    /**
     * @return string
     */
    abstract protected function getSectionsJson(): string;

    /**
     * @return string
     */
    abstract protected function getText(): string;


    /**
     * Return info block html
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if ($this->getModuleVersion->execute($this->getModuleName() . $this->getMinPlan())) {
            return '';
        }

        $html = '';

        if ($text = $this->getText()) {
            $textHtml = '<div style="padding:10px;background-color:#f8f8f8;border:1px solid #ddd;margin-bottom:7px;">';
            $textHtml .= $text . ' <a style="color: #ef672f; text-decoration: underline;" href="https://magefan.com/magento-2-translation-extension/pricing?utm_source=admin&utm_medium=config&utm_campaign=upgrade-link" target="_blank">Read more</a>.';
            $textHtml .= '</div>';
        }

        $plan = ($this->getMinPlan() == 'Extra') ? 'Extra' : 'Plus or Extra';

        $script = '
                require(["jquery", "Magefan_Translation/js/mf-upgrade-plan-popup", "domReady!"], function($, mfPopup){
                    function showAlert() {
                        mfPopup("' . $plan . '", "config");
                    }

                    setInterval(function(){
                        var sections = ' . $this->getSectionsJson() . ';

                        sections.forEach(function(sectionId) {
                            var $section = $("#" + sectionId + "-state").parent(".section-config");
                            if (!$section.length) {
                                $section = $("#" + sectionId).parents("tr:first");
                            } else {
                                var $fieldset = $section.find("fieldset:first");
                                if (!$fieldset.data("mfftext")) {
                                    $fieldset.data("mfftext", 1);
                                    $fieldset.prepend(\'' . $textHtml . '\');
                                }
                            }

                            $section.find(".use-default").css("visibility", "hidden");
                            $section.find("input,select").each(function(){
                                $(this).attr("readonly", "readonly");
                                $(this).removeAttr("disabled");
                                if ($(this).data("mffdisabled")) return;
                                $(this).data("mffdisabled", 1);
                                $(this).click(function(){
                                    $(this).val($(this).data("mfOldValue")).trigger("change");
                                    showAlert();
                                }).on("focus", function() {
                                    $(this).data("mfOldValue", $(this).val());
                                });

                                $section.find("button").each(function() {
                                    $(this).off("click");
                                    $(this).click(function() {
                                        showAlert();
                                    });
                                });
                            });
                        });
                    }, 1000);
                });
        ';

        $html .= $this->mfSecureRenderer->renderTag('script', [], $script, false);

        return $html;
    }
}
