<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Controller\Adminhtml\Promo;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

abstract class AbstractPromo extends Action
{
    const ADMIN_RESOURCE = 'Magefan_Translation::elements';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    abstract protected function getPageTitle(): string;

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__($this->getPageTitle()));
        return $resultPage;
    }
}
