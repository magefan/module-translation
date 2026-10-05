<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Controller\Adminhtml\Promo;

use Magefan\Community\Controller\Adminhtml\AbstractPromo as CommunityAbstractPromo;

abstract class AbstractPromo extends CommunityAbstractPromo
{
    public const ADMIN_RESOURCE = 'Magefan_Translation::elements';
}