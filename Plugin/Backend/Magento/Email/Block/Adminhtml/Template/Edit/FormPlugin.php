<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Email\Block\Adminhtml\Template\Edit;

use Magefan\Translation\Plugin\Backend\AbstractLegacyEditFormPlugin;

/**
 * Basic-tier teaser for the "Exclude From Auto Translation" email template field.
 *
 * Registered under the same plugin name
 * ("magefan_translation_plus_email_template_edit_form_use_default") as
 * Magefan\TranslationPlus\Plugin\Magento\Email\Block\Adminhtml\Template\Edit\FormPlugin,
 * which replaces it when Plus is installed.
 */
class FormPlugin extends AbstractLegacyEditFormPlugin
{
    /**
     * Fieldset of the core email template form the auto translation one goes after
     */
    const BASE_FIELDSET = 'base_fieldset';

    /**
     * Add the locked field, for saved templates only.
     *
     * @param \Magento\Email\Block\Adminhtml\Template\Edit\Form $subject
     * @param \Magento\Framework\Data\Form|null $form
     * @return \Magento\Framework\Data\Form|null
     */
    public function afterGetForm($subject, $form)
    {
        if (!$form || !$this->config->isEnabled() || !$this->isSavedTemplate($subject)) {
            return $form;
        }

        $this->addLockedExcludeAutoTranslationField($form);

        return $form;
    }

    /**
     * After the core "Template Information" fieldset.
     *
     * @inheritdoc
     */
    protected function getBaseFieldsetId(): string
    {
        return self::BASE_FIELDSET;
    }

    /**
     * @inheritdoc
     */
    protected function getUpgradePopupSource(): string
    {
        return 'email-template-edit';
    }

    /**
     * New templates are not auto translated until saved, same as the Auto Translate button.
     *
     * @param \Magento\Email\Block\Adminhtml\Template\Edit\Form $subject
     * @return bool
     */
    protected function isSavedTemplate($subject): bool
    {
        $template = $subject->getEmailTemplate();

        return $template && $template->getId();
    }
}
