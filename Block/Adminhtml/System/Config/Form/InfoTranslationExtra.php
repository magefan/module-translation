<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

class InfoTranslationExtra extends InfoPlan
{
    /**
     * @return string
     */
    protected function getMinPlan(): string
    {
        return 'Extra';
    }

    /**
     * @return string
     */
    protected function getSectionsJson(): string
    {
        $sections = json_encode([
            'mftranslation_mftranslationapis',
            'mftranslation_auto_translation'
        ]);
        return $sections;
    }

    /**
     * @return string
     */
    protected function getText(): string
    {
        return (string)__("This option is available in <strong>%1</strong> plan only.", $this->getMinPlan());
    }
}
