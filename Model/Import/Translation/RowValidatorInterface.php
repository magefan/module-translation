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
       const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
       const ERROR_TITLE_IS_EMPTY = 'EmptyTITLE';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}
