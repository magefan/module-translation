<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\ScopeInterface;

class Translation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Translation constructor
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('translation', 'key_id');
    }

    /**
     * @param AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getData('mf_locale')) {
            $object->setData('locale', $object->getData('mf_locale'));
        }

        $storeId = $object->getData('store_id') ?: 0;
        $crcString = $object->getData('string') ?: '';
        if ($storeId > 0) {
            $object->setData('locale', $this->scopeConfig->getValue(
                'general/locale/code',
                ScopeInterface::SCOPE_STORE,
                $storeId
            ));
        }
        $object->setData('crc_string', crc32($crcString));
        return parent::_beforeSave($object);
    }
}
