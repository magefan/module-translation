<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit;

use Magefan\Translation\Model\Config;
use Magefan\Translation\Model\LockedToggleFieldHtml;

/**
 * Basic-tier teaser for the "Exclude From Auto Translation" review field. Registered
 * under the same plugin name ("magefan_translation_plus_review_edit_form_use_default")
 * as Magefan\TranslationPlus\Plugin\Magento\Review\Block\Adminhtml\Edit\FormPlugin on
 * the same target class - TranslationPlus loads after Translation, so its plugin
 * definition overrides this one entirely (the real, working fieldset) when Plus and
 * Extra are both installed.
 *
 * That subclass also falls back to addLockedExcludeAutoTranslationField() below
 * whenever Extra specifically is missing (Plus alone doesn't act on this flag - see
 * that class), which is why the shared bits here are protected rather than private.
 *
 * Mirrors Magefan\Translation\Plugin\Backend\Magento\Review\Block\Adminhtml\Edit (the
 * "Auto Translate" button teaser) and the locked "Auto Translation (Extra)" fieldset
 * category/product forms already show (Magefan_Translation/js/form/components/locked-fieldset) -
 * this is the same idea, hand-built, because the review edit form is a legacy
 * Widget\Form\Generic, not a UI component.
 */
class FormPlugin
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var LockedToggleFieldHtml
     */
    protected $lockedToggleFieldHtml;

    /**
     * @param Config $config
     * @param LockedToggleFieldHtml $lockedToggleFieldHtml
     */
    public function __construct(
        Config $config,
        LockedToggleFieldHtml $lockedToggleFieldHtml
    ) {
        $this->config = $config;
        $this->lockedToggleFieldHtml = $lockedToggleFieldHtml;
    }

    /**
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
     * Adds the locked "Exclude From Auto Translation" fieldset: the same toggle a
     * working install would show, disabled, with a click-anywhere overlay that opens
     * the upgrade popup instead of doing anything.
     *
     * Also called by Magefan\TranslationPlus\...\FormPlugin, which extends this class,
     * for its own Extra-less fallback - guarded the same way afterGetForm() above
     * guards it, so either caller can call it unconditionally.
     *
     * @param \Magento\Framework\Data\Form $form
     * @return void
     */
    protected function addLockedExcludeAutoTranslationField($form)
    {
        if (!$form->getElement('review_details') || $form->getElement('mf_auto_translation')) {
            return;
        }

        $fieldset = $form->addFieldset(
            'mf_auto_translation',
            ['legend' => __('Auto Translation (Extra)')],
            'review_details'
        );

        $fieldset->addField(
            'mf_exclude_auto_translation',
            'note',
            [
                'label' => __('Exclude From Auto Translation'),
                'text' => $this->lockedToggleFieldHtml->render('Extra', 'review-edit', 'fieldset'),
            ]
        );
    }
}
