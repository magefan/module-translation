<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

class InfoTranslationPlus extends InfoPlan
{
    /**
     * Get min plan.
     *
     * @return string
     */
    protected function getMinPlan(): string
    {
        return 'Plus';
    }

    /**
     * Get sections json.
     *
     * @return string
     */
    protected function getSectionsJson(): string
    {
        $sections = json_encode([
            'mftranslation_general_flush_cache_on_translation',
            'mftranslation_general_ignore_gws_permissions'
        ]);
        return $sections;
    }

    /**
     * Get text.
     *
     * @return string
     */
    protected function getText(): string
    {
        return (string)__("This option is available in <strong>%1</strong> plan only.", $this->getMinPlan());
    }
}
