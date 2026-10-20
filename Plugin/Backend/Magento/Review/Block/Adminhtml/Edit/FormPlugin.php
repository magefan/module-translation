<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit;

use Magefan\Translation\Plugin\Backend\AbstractLegacyEditFormPlugin;

/**
 * Basic-tier teaser for the "Exclude From Auto Translation" review field. Registered
 * under the same plugin name ("magefan_translation_plus_review_edit_form_use_default")
 * as Magefan\TranslationPlus\Plugin\Magento\Review\Block\Adminhtml\Edit\FormPlugin on
 * the same target class - TranslationPlus loads after Translation, so its plugin
 * definition overrides this one entirely (the real, working fieldset) when Plus and
 * Extra are both installed.
 *
 * Mirrors Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit (the
 * "Auto Translate" button teaser).
 */
class FormPlugin extends AbstractLegacyEditFormPlugin
{
    /**
     * After get form.
     *
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
     * @inheritdoc
     */
    protected function getBaseFieldsetId(): string
    {
        return 'review_details';
    }

    /**
     * @inheritdoc
     */
    protected function getUpgradePopupSource(): string
    {
        return 'review-edit';
    }
}
