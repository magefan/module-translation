<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Api\Data;

/**
 * Interface TranslationInterface
 */
interface TranslationInterface
{

    public const KEY_ID = 'key_id';

    public const STRING  = 'string';

    public const STORE_ID = 'store_id';

    public const TRANSLATE = 'translate';

    public const LOCALE = 'locale';

    public const CRC_STRING = 'crc_string';

    /**
     * Get id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get string.
     *
     * @return mixed
     */
    public function getString();

    /**
     * Get store id.
     *
     * @return mixed
     */
    public function getStoreId();

    /**
     * Get translate.
     *
     * @return mixed
     */
    public function getTranslate();

    /**
     * Get locale.
     *
     * @return mixed
     */
    public function getLocale();

    /**
     * Get crc string.
     *
     * @return mixed
     */
    public function getCrcString();

    /**
     * Set id.
     *
     * @param int $id
     * @return mixed
     */
    public function setId($id);

    /**
     * Set string.
     *
     * @param string $string
     * @return mixed
     */
    public function setString($string);

    /**
     * Set store id.
     *
     * @param int $storeid
     * @return mixed
     */
    public function setStoreId($storeid);

    /**
     * Set translate.
     *
     * @param string $translate
     * @return mixed
     */
    public function setTranslate($translate);

    /**
     * Set locale.
     *
     * @param string $locale
     * @return mixed
     */
    public function setLocale($locale);

    /**
     * Set crc string.
     *
     * @param int $crcstring
     * @return mixed
     */
    public function setCrcString($crcstring);
}
