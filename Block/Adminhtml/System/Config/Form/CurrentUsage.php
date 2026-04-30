<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magefan\Translation\Model\Config;

class CurrentUsage extends Field
{

    const CURRENT_USAGE_TEMPLATE = 'system/config/current_usage.phtml';

    /**
     * @return $this|Field
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (!$this->getTemplate()) {
            $this->setTemplate(static::CURRENT_USAGE_TEMPLATE);
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
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->addData([
            'used'=> ObjectManager::getInstance()->create(Config::class)->getTodayUsage()
        ]);

        return $this->_toHtml();
    }
}
