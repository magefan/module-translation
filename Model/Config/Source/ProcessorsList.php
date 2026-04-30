<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class ProcessorsList implements \Magento\Framework\Option\ArrayInterface
{
    const GOOGLE_TRANSLATE_PROCESSOR = 'gt';
    const CHAT_GPT_PROCESSOR = 'chat_gpt';
    const DEEPL_PROCESSOR = 'deepl';

    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        $result = [
            ['value' => self::GOOGLE_TRANSLATE_PROCESSOR, 'label' => 'Google Translate'],
            ['value' => self::CHAT_GPT_PROCESSOR,         'label' => 'ChatGPT'],
            ['value' => self::DEEPL_PROCESSOR,            'label' => 'DeepL']
        ];

        return $result;
    }


    /**
     * @param string $processorCode
     * @return string
     */
    public function getProcessorLabel(string $processorCode): string
    {
        $label = $processorCode;

        foreach ($this->toOptionArray() as $processor) {
            if ($processor['value'] === $processorCode) {
                $label = $processor['label'];
                break;
            }
        }

        return $label;
    }
}
