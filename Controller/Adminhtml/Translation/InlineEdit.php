<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Controller\Adminhtml\Translation;

use Magento\Backend\App\Action\Context;
use Magefan\Translation\Api\TranslationRepositoryInterface as TranslationRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Magefan\Translation\Api\Data\TranslationInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magefan_Translation::addedit';

    /**
     * @var TranslationRepository
     */
    protected $translationRepository;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param TranslationRepository $translationRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        TranslationRepository $translationRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->translationRepository = $translationRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $translationId) {
            $translation = $this->translationRepository->getById($translationId);
            try {
                $translationData = $postItems[$translationId];
                $extendedTranslationData = $translation->getData();
                $this->setTranslationData($translation, $extendedTranslationData, $translationData);
                $this->translationRepository->save($translation);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithTranslationId($translation, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithTranslationId($translation, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithTranslationId(
                    $translation,
                    __('Something went wrong while saving the translation.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * @param TranslationInterface $translation
     * @param $errorText
     * @return string
     */
    protected function getErrorWithTranslationId(TranslationInterface $translation, $errorText)
    {
        return '[KEY_ID: ' . $translation->getId() . '] ' . $errorText;
    }

    /**
     * @param \Magefan\Translation\Model\Translation $translation
     * @param array $extendedTranslationData
     * @param array $translationData
     * @return $this
     */
    public function setTranslationData(
        \Magefan\Translation\Model\Translation $translation,
        array $extendedTranslationData,
        array $translationData
    ) {
        $translation->setData(array_merge($translation->getData(), $extendedTranslationData, $translationData));
        return $this;
    }
}
