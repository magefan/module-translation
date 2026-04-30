<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ScheduleSelectedSourcesButton extends Field
{
    /**
     * Template for button
     */
    const BUTTON_TEMPLATE = 'Magefan_Translation::system/config/buttons/schedule_selected_sources_button.phtml';

    /**
     * @return $this|Field
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (!$this->getTemplate()) {
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        return $this;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * @return string
     */
    public function getScheduleSelectedSourcesUrl(): string
    {
        return $this->getUrl('translationextra/system/addselectedsourcestoschedule/');
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->addData([
            'id' => 'schedule_selected_sources_button',
            'button_label' => __('Schedule Selected Sources')
        ]);
        return $this->_toHtml();
    }
}
