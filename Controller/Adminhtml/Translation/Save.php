<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Controller\Adminhtml\Translation;

use Magento\Backend\App\Action;
use Magefan\Translation\Model\Translation;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magefan_Translation::addedit';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var mixed
     */
    private $translationFactory;

    /**
     * @var mixed
     */
    private $translationRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Magefan\Translation\Model\TranslationFactory|null $translationFactory
     * @param \Magefan\Translation\Api\TranslationRepositoryInterface|null $translationRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Magefan\Translation\Model\TranslationFactory $translationFactory = null,
        \Magefan\Translation\Api\TranslationRepositoryInterface $translationRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->translationFactory = $translationFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magefan\Translation\Model\TranslationFactory::class);
        $this->translationRepository = $translationRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magefan\Translation\Api\TranslationRepositoryInterface::class);
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Translation::STATUS_ENABLED;
            }
            if (empty($data['key_id'])) {
                $data['key_id'] = null;
            }

            /** @var \Magefan\Translation\Model\Translation $model */
            $model = $this->translationFactory->create();

            $id = $this->getRequest()->getParam('key_id');
            if ($id) {
                try {
                    $model = $this->translationRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This translation no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'translation_translation_prepare_save',
                ['translation' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->translationRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the translation.'));
                $this->dataPersistor->clear('translation_translation');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['key_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the translation.'));
            }

            $this->dataPersistor->set('translation_translation', $data);
            return $resultRedirect->setPath('*/*/edit', ['key_id' => $this->getRequest()->getParam('key_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
