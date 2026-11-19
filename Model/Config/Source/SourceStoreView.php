<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\System\Store as SystemStore;

class SourceStoreView implements OptionSourceInterface
{
    /**
     * @var SystemStore
     */
    private $systemStore;

    /**
     * @param SystemStore $systemStore
     */
    public function __construct(SystemStore $systemStore)
    {
        $this->systemStore = $systemStore;
    }

    /**
     * "All Store Views" and store views grouped by website and store
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return $this->systemStore->getStoreValuesForForm(false, true);
    }
}
