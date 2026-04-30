<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Controller\Adminhtml\Mass\Translate;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Locked extends Action
{
    public const ADMIN_RESOURCE = 'Magento_Backend::admin';

    public function execute()
    {
        $url = 'https://magefan.com/magento-2-translation-extension/pricing';
        $params = '?utm_source=admin&utm_medium=auto-translate&utm_campaign=mass-action';

        $this->messageManager->addNotice(
            __(
                'This option is available in Magefan Translation Extra plan only. Please upgrade at <a href="%1" target="_blank">' .$url. '</a>.',
                $url . $params
            )
        );

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
