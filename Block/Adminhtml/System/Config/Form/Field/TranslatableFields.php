<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types = 1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form\Field;

use Magefan\Translation\Model\TranslatableData;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class TranslatableFields extends Select
{
    /**
     * @var
     */
    private $translatableData;

    /**
     * @param Context $context
     * @param TranslatableData $translatableData
     * @param array $data
     */
    public function __construct(
        Context          $context,
        TranslatableData $translatableData,
        array            $data = []
    ) {
        $this->translatableData = $translatableData;
        parent::__construct($context, $data);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->addOption(0, ' ');

            foreach ($this->translatableData->getList() as $key => $translatableAttributes) {
                $label = ucwords(str_replace('_', ' ', $key));

                $this->addOption(
                    '',
                    '---- ' . $label . ' ---',
                    ['disabled' => true]
                );

                foreach ($translatableAttributes as $translatableAttribute) {
                    $params = [];

                    $this->addOption(
                        $key .'/'. $translatableAttribute['code'],
                        $label . ' - ' . $translatableAttribute['label'],
                        $params
                    );
                }
            }
        }

        return parent::_toHtml();
    }
}
