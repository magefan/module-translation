<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class ChatGPTModels implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var string[]
     */
    private $models = [
        'gpt-5.2',

        'gpt-5',
        'gpt-5-mini',
        'gpt-5-nano',

        'gpt-4.1',
        'gpt-4.1-mini',
        'gpt-4.1-nano',

        'gpt-4',
        'gpt-4o',
        'gpt-4o-mini',

        'gpt-3.5-turbo'
    ];

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach ($this->models as $modelCode) {
            $result[] = ['value' => $modelCode, 'label' => $modelCode];
        }

        return $result;
    }
}
