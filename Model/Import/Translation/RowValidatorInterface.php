<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Import\Translation;

/**
 * Interface RowValidatorInterface
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    public const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
    public const ERROR_TITLE_IS_EMPTY = 'EmptyTITLE';
    public const ERROR_STORE_ID_NOT_EXIST = 'StoreIdNotExist';

    /**
     * Initialize validator
     *
     * @param \Magento\CatalogImportExport\Model\Import\Product $context
     * @return $this
     */
    public function init($context);
}
