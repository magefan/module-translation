<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model;

use Magefan\Translation\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Magefan Blog Config Model
 */
class Config implements ConfigInterface
{
    /**
     * Extension enabled config path
     */
    const XML_PATH_EXTENSION_ENABLED = 'mftranslation/general/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve true if translateplus module is enabled
     *
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_EXTENSION_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
