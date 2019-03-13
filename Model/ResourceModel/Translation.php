<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Translation
 * @package Magefan\Translation\Model\ResourceModel
 */
class Translation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Translation constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);
        $this->_date = $date;
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
     * @return mixed
     *
     *
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $value = $object->getData('store_id') ?: null;
        $crc_string = $object->getData('string') ?: null;
        ($value =='')?: 0;
        if ($value > 0) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $store = $objectManager->get('Magento\Framework\Locale\Resolver');
            $object->setData('locale', $store->getLocale());
        }
        $object->setData('crc_string', crc32($crc_string));
        return parent::_beforeSave($object);
    }
}
