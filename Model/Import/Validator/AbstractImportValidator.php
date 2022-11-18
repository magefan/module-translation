<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model\Import\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Magefan\Translation\Model\Import\Translation\RowValidatorInterface;

/**
 * Class AbstractImportValidator
 * @package Magefan\Translation\Model\Import\Validator
 */
abstract class AbstractImportValidator extends AbstractValidator implements RowValidatorInterface
{
    /**
     * @var \Magento\CatalogImportExport\Model\Import\Product
     */
    protected $context;

    /**
     * @param \Magento\CatalogImportExport\Model\Import\Product $context
     * @return $this
     */
    public function init($context)
    {
        $this->context = $context;
        return $this;
    }
}
