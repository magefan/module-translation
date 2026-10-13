<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Email\Block\Adminhtml\Template\Edit;

use Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit\FormPlugin as ReviewFormPlugin;

/**
 * Basic-tier teaser for the "Exclude From Auto Translation" email template field.
 *
 * The email template form is a legacy Widget\Form\Generic like the review one, so the
 * locked field is built the same way - only the fieldset it is inserted after differs.
 * Registered under the same plugin name
 * ("magefan_translation_plus_email_template_edit_form_use_default") as
 * Magefan\TranslationPlus\Plugin\Magento\Email\Block\Adminhtml\Template\Edit\FormPlugin,
 * which replaces it when Plus is installed.
 */
class FormPlugin extends ReviewFormPlugin
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
     * Same as the review one, after the core "Template Information" fieldset instead.
     *
     * @param \Magento\Framework\Data\Form $form
     * @return \Magento\Framework\Data\Form\Element\Fieldset|null
     */
    protected function addAutoTranslationFieldset($form)
    {
        if (!$form->getElement(self::BASE_FIELDSET) || $form->getElement('mf_auto_translation')) {
            return null;
        }

        return $form->addFieldset(
            'mf_auto_translation',
            ['legend' => __('Auto Translation (Extra)')],
            self::BASE_FIELDSET
        );
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
