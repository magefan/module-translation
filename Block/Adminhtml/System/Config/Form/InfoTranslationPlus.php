<?php


namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

class InfoTranslationPlus extends InfoPlan
{

    protected function getMinPlan(): string
    {
        return 'Plus';
    }

    protected function getSectionsJson(): string
    {
        $sections = json_encode([
            'mftranslation_general_flush_cache_on_translation',
            'mftranslation_general_ignore_gws_permissions'
        ]);
        return $sections;
    }

    protected function getText(): string
    {
        return (string)__("This option is available in <strong>%1</strong> plan only.", $this->getMinPlan());
    }
}
