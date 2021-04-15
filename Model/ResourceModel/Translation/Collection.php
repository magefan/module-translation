<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\ResourceModel\Translation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'key_id';

    protected $_eventPrefix = 'translation_translation_collection';

    protected $_eventObject = 'translation_collection';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            \Magefan\Translation\Model\Translation::class,
            \Magefan\Translation\Model\ResourceModel\Translation::class
        );
    }
}
