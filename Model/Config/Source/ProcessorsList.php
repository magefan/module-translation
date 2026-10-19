<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

class ProcessorsList implements \Magento\Framework\Option\ArrayInterface
{
    public const GOOGLE_TRANSLATE_PROCESSOR = 'gt';
    public const CHAT_GPT_PROCESSOR = 'chat_gpt';
    public const DEEPSEEK_PROCESSOR = 'deepseek';
    public const DEEPL_PROCESSOR = 'deepl';

    /**
     * Get options as value-label pairs.
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        $result = [
            ['value' => self::GOOGLE_TRANSLATE_PROCESSOR, 'label' => 'Google Translate'],
            ['value' => self::CHAT_GPT_PROCESSOR,         'label' => 'ChatGPT'],
            ['value' => self::DEEPSEEK_PROCESSOR,         'label' => 'DeepSeek'],
            ['value' => self::DEEPL_PROCESSOR,            'label' => 'DeepL']
        ];

        return $result;
    }

    /**
     * Get processor label.
     *
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
