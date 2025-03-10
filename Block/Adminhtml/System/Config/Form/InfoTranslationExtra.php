<?php


namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

class InfoTranslationExtra extends InfoPlan
{

    protected function getMinPlan(): string
    {
        return 'Extra';
    }

    protected function getSectionsJson(): string
    {
        $sections = json_encode([
            'mftranslation_mftranslationapis_locale_source',
            'mftranslation_mftranslationapis_mfgoogleapi',
            'mftranslation_mftranslationapis_mfchatgptapi',
            'mftranslation_mftranslationapis_mfdeeplapi',
        ]);
        return $sections;
    }

    protected function getText(): string
    {
        return (string)__("This option is available in <strong>%1</strong> plan only.", $this->getMinPlan());
    }
}
