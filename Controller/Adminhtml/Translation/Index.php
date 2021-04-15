<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Controller\Adminhtml\Translation;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magefan_Translation::addedit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magefan\Translation\Api\ConfigInterface
     */
    private $configInterface;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magefan\Translation\Api\ConfigInterface $configInterface
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirectFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magefan\Translation\Api\ConfigInterface $configInterface,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\Redirect $resultRedirectFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configInterface = $configInterface;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        if (!$this->configInterface->isEnabled()) {
            $this->messageManager->addError(__(strrev('noitalsnarT> snoisnetxE nafegaM > noitarugifnoC >
            serotS ot etagivan esaelp noisnetxe eht elbane ot ,delbasid si noitalsnarT nafegaM')));

            return $this->resultRedirectFactory->setPath('admin/index/index');
        }

        $resultPage->getConfig()->getTitle()->prepend(__('Translations'));

        return $resultPage;
    }
}
