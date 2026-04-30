<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types = 1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form\Field;

class SkippedFieldsDynamicRow extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\View\Element\BlockInterface
     */
    protected $translatableFields;

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getTranslatableFiels()
    {
        if (!$this->translatableFields) {
            $this->translatableFields = $this->createBlock(\Magefan\Translation\Block\Adminhtml\System\Config\Form\Field\TranslatableFields::class);
            $this->translatableFields->setClass('translatable_fields_select');
        }
        return $this->translatableFields;
    }

    /**
     * @param $object
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function createBlock($object)
    {
        return $this->getLayout()->createBlock(
            $object,
            '',
            ['data' =>
                ['is_render_to_js_template' => true]
            ]
        );
    }

    /**
     * Prepare to render
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'translatable_fields',
            [
                'label' => __('Field'),
                'renderer' => $this->_getTranslatableFiels()
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return bool
     */
    protected function _isInheritCheckboxRequired(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return false;
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];

        $optionExtraAttr['option_' . $this->_getTranslatableFiels()->calcOptionHash($row->getData('translatable_fields'))] = 'selected="selected"';

        $row->setData('option_extra_attrs', $optionExtraAttr);
    }

    /**
     * @return \Magento\Framework\Phrase|string
     * @throws \Exception
     */
    protected function _toHtml()
    {
        try {
            return parent::_toHtml();
        } catch (\Mautic\Exception\RequiredParameterMissingException $e) {
            return __(
                '<strong>Cannot Display Skipped Fields</strong>.',
                $this->escapeHtml($e->getMessage())
            );
        }
    }
}
