<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Controller\Adminhtml\Translation;

use Magento\Backend\App\Action;
use Magefan\Translation\Api\TranslationRepositoryInterface as TranslationRepository;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magefan_Translation::addedit';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var TranslationRepository
     */
    protected $translationRepository;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param TranslationRepository $translationRepository
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        TranslationRepository $translationRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->translationRepository = $translationRepository;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Translation
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Translation $resultPage */

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magefan_Translation::addedit')
            ->addBreadcrumb(__('Translation'), __('Translation'))
            ->addBreadcrumb(__('Manage Translation'), __('Manage Translation'));
        return $resultPage;
    }

    /**
     * Edit Translation
     *
     * @return \Magento\Backend\Model\View\Result\Translation|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('key_id');
        $model = $this->_objectManager->create(\Magefan\Translation\Model\Translation::class);

        if ($id) {
            $model = $this->translationRepository->getById($id);

            if ($model->getById()) {
                $this->messageManager->addError(__('This translation no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('translation_translation', $model);

        /** @var \Magento\Backend\Model\View\Result\Translation $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Translation') : __('Add Translation'),
            $id ? __('Edit Translation') : __('Add Translation')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Translation'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getById() ? $model->getTitle() : __('Add Translation'));

        return $resultPage;
    }
}
