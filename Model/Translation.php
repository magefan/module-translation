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
    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 0;

    const CACHE_TAG = 'mftr_';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'magefan_translation';

    /*
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
     *
     */
    protected function _construct()
    {
        $this->_init(\Magefan\Translation\Model\ResourceModel\Translation::class);
    }

    /**
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
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return parent::getData(self::KEY_ID);
    }

    /**
     * @return mixed
     */
    public function getString()
    {
        return $this->getData(self::STRING);
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @return mixed
     */
    public function getTranslate()
    {
        return $this->getData(self::TRANSLATE);
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->getData(self::LOCALE);
    }

    /**
     * @return mixed
     */
    public function getCrcString()
    {
        return $this->getData(self::CRC_STRING);
    }

    /**
     * @param mixed $id
     * @return Translation|AbstractModel|mixed
     */
    public function setId($id)
    {
        return $this->setData(self::KEY_ID, $id);
    }

    /**
     * @param $string
     * @return Translation|mixed
     */
    public function setString($string)
    {
        return $this->setData(self::STRING, $string);
    }

    /**
     * @param $storeid
     * @return Translation|mixed
     */
    public function setStoreId($storeid)
    {
        return $this->setData(self::STORE_ID, $storeid);
    }

    /**
     * @param $translate
     * @return Translation|mixed
     */
    public function setTranslate($translate)
    {
        return $this->setData(self::TRANSLATE, $translate);
    }

    /**
     * @param $locale
     * @return Translation|mixed
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * @param $crcstring
     * @return Translation|mixed
     */
    public function setCrcString($crcstring)
    {
        return $this->setData(self::CRC_STRING, $crcstring);
    }
}
