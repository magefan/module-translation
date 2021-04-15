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

    const KEY_ID = 'key_id';

    const STRING  = 'string';

    const STORE_ID = 'store_id';

    const TRANSLATE = 'translate';

    const LOCALE = 'locale';

    const CRC_STRING = 'crc_string';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getString();

    /**
     * @return mixed
     */
    public function getStoreId();

    /**
     * @return mixed
     */
    public function getTranslate();

    /**
     * @return mixed
     */
    public function getLocale();

    /**
     * @return mixed
     */
    public function getCrcString();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);

    /**
     * @param $string
     * @return mixed
     */
    public function setString($string);

    /**
     * @param $storeid
     * @return mixed
     */
    public function setStoreId($storeid);

    /**
     * @param $translate
     * @return mixed
     */
    public function setTranslate($translate);

    /**
     * @param $locale
     * @return mixed
     */
    public function setLocale($locale);

    /**
     * @param $crcstring
     * @return mixed
     */
    public function setCrcString($crcstring);
}
