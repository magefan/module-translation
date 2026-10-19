<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model;

use Magefan\Translation\Api\Data\TranslationInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Translation extends AbstractModel implements TranslationInterface, IdentityInterface
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 0;

    public const CACHE_TAG = 'mftr_';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'magefan_translation';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'translation';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\Magefan\Translation\Model\ResourceModel\Translation::class);
    }

    /**
     * Get identities for cache.
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        $identities[] =  self::CACHE_TAG . '_' . $this->getId();
        //if ($this->getData('key_id') && $this->getData('key_id') != $this->getOrigData('key_id')) {
            $identities[] = self::CACHE_TAG . '_' . 0;
        //}

        return $identities;
    }

    /**
     * Get default values.
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    /**
     * Get available statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get id.
     *
     * @return mixed
     */
    public function getId()
    {
        return parent::getData(self::KEY_ID);
    }

    /**
     * Get string.
     *
     * @return mixed
     */
    public function getString()
    {
        return $this->getData(self::STRING);
    }

    /**
     * Get store id.
     *
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Get translate.
     *
     * @return mixed
     */
    public function getTranslate()
    {
        return $this->getData(self::TRANSLATE);
    }

    /**
     * Get locale.
     *
     * @return mixed
     */
    public function getLocale()
    {
        return $this->getData(self::LOCALE);
    }

    /**
     * Get crc string.
     *
     * @return mixed
     */
    public function getCrcString()
    {
        return $this->getData(self::CRC_STRING);
    }

    /**
     * Set id.
     *
     * @param mixed $id
     * @return Translation|AbstractModel|mixed
     */
    public function setId($id)
    {
        return $this->setData(self::KEY_ID, $id);
    }

    /**
     * Set string.
     *
     * @param string $string
     * @return Translation|mixed
     */
    public function setString($string)
    {
        return $this->setData(self::STRING, $string);
    }

    /**
     * Set store id.
     *
     * @param int $storeid
     * @return Translation|mixed
     */
    public function setStoreId($storeid)
    {
        return $this->setData(self::STORE_ID, $storeid);
    }

    /**
     * Set translate.
     *
     * @param string $translate
     * @return Translation|mixed
     */
    public function setTranslate($translate)
    {
        return $this->setData(self::TRANSLATE, $translate);
    }

    /**
     * Set locale.
     *
     * @param string $locale
     * @return Translation|mixed
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * Set crc string.
     *
     * @param int $crcstring
     * @return Translation|mixed
     */
    public function setCrcString($crcstring)
    {
        return $this->setData(self::CRC_STRING, $crcstring);
    }
}
