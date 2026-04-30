<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types = 1);

namespace Magefan\Translation\Model\System\Config\Backend;

use Magento\Framework\Math\Random;

/**
 * Class MapHelper manage mapped values.
 */
class MapHelper
{
    /**
     * @var Random
     */
    protected $mathRandom;

    /**
     * @param Random $mathRandom
     */
    public function __construct(
        Random $mathRandom
    ) {
        $this->mathRandom = $mathRandom;
    }

    /**
     * Generate a storable representation of a value
     *
     * @param int|float|string|array $value
     * @return string
     */
    public function serializeValue($value)
    {
        if (is_numeric($value)) {
            $data = (float) $value;
            return (string) $data;
        } elseif (is_array($value)) {
            $data = [];
            foreach ($value as $attributeCode) {
                if (!array_key_exists($attributeCode, $data)) {
                    $data[$attributeCode] = $attributeCode;
                }
            }
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            return '';
        }
    }

    /**
     * Create a value from a storable representation
     *
     * @param int|float|string $value
     * @return array
     */
    public function unserializeValue($value)
    {
        if (is_string($value) && !empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    /**
     * Check whether value is in form retrieved by _encodeArrayFieldValue()
     *
     * @param string|array $value
     * @return bool
     */
    protected function isEncodedArrayFieldValue($value)
    {
        if (!is_array($value)) {
            return false;
        }

        unset($value['__empty']);

        foreach ($value as $row) {
            if (!is_array($row)
                || !array_key_exists('translatable_fields', $row)
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Encode value to be used in \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array $value
     * @return array
     */
    protected function encodeArrayFieldValue(array $value)
    {
        $result = [];

        foreach ($value as $attributeCode) {
            $resultId = $this->mathRandom->getUniqueHash('_');
            $result[$resultId] = ['translatable_fields' => $attributeCode];
        }

        return $result;
    }

    /**
     * Decode value from used in \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array $value
     * @return array
     */
    protected function decodeArrayFieldValue(array $value)
    {
        $result = [];

        unset($value['__empty']);

        foreach ($value as $row) {

            if (!is_array($row)
                || !array_key_exists('translatable_fields', $row)
            ) {
                continue;
            }

            if (!in_array($row['translatable_fields'], $result)) {
                $result[] = $row['translatable_fields'];
            }
        }

        return $result;
    }

    /**
     * Make value readable by \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param string|array $value
     * @return array
     */
    public function makeArrayFieldValue($value)
    {
        $value = $this->unserializeValue($value);

        if (!$this->isEncodedArrayFieldValue($value)) {
            $value = $this->encodeArrayFieldValue($value);
        }
        return $value;
    }

    /**
     * Make value ready for store
     *
     * @param string|array $value
     * @return string
     */
    public function makeStorableArrayFieldValue($value)
    {
        if ($this->isEncodedArrayFieldValue($value)) {
            $value = $this->decodeArrayFieldValue($value);
        }

        sort($value);

        return $this->serializeValue($value);
    }
}
