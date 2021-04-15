<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Api;

/**
 *
 * @api
 * @since 2.1.0
 */
interface ConfigInterface
{
    /**
     * @return bool
     */
    public function isEnabled();
}
