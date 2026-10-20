<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Magento\Ui\Model\Export;

class MetadataProvider
{
    /**
     * Use the field names as headers of the translation and glossary grid exports.
     *
     * @param \Magento\Ui\Model\Export\MetadataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetHeaders(
        \Magento\Ui\Model\Export\MetadataProvider $subject,
        $result
    ) {
        $id = trim(str_replace('"', '', $result[0] ?? ''));
        if ('ID' == $id && 'Original Text' == trim($result[1]) && 'Translate Text' ==  trim($result[3])) {
            $result = [
                'key_id',
                'string',
                'store_id',
                'translate',
                'locale'];
        }

        if (in_array('Source Text', $result) || in_array('Source Language Code', $result)) {
            $result = $this->convertToSnakeCase($result);
        }

        return $result;
    }

    /**
     * Convert to snake case.
     *
     * @param array $input
     * @return array
     */
    private function convertToSnakeCase(array $input): array
    {
        return array_map(function ($item) {
            return str_replace(' ', '_', strtolower($item));
        }, $input);
    }

    /**
     * Remove the locale and store_id options when the export is made by Magefan Translation.
     *
     * @param \Magento\Ui\Model\Export\MetadataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetOptions(
        \Magento\Ui\Model\Export\MetadataProvider $subject,
        $result
    ) {
        if (false !== strpos(\Magento\Framework\Debug::backtrace(true), 'Magefan\Translation')) {
            if (isset($result['locale'])) {
                unset($result['locale']);
            }
            if (isset($result['store_id'])) {
                unset($result['store_id']);
            }
        }

        return $result;
    }
}
