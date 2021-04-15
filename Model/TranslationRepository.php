<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Model;

use Magefan\Translation\Api\Data;
use Magefan\Translation\Api\TranslationRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magefan\Translation\Model\ResourceModel\Translation as ResourceTranslation;
use Magefan\Translation\Model\ResourceModel\Translation\CollectionFactory as TranslationCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @var ResourceTranslation
     */
    protected $resource;

    /**
     * @var TranslationFactory
     */
    protected $translationFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var Data\TranslationInterfaceFactory
     */
    protected $dataTranslationFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * TranslationRepository constructor.
     * @param ResourceTranslation $resource
     * @param TranslationFactory $translationFactory
     * @param Data\TranslationInterfaceFactory $dataTranslationFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceTranslation $resource,
        TranslationFactory $translationFactory,
        Data\TranslationInterfaceFactory $dataTranslationFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->translationFactory = $translationFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTranslationFactory = $dataTranslationFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function save(\Magefan\Translation\Api\Data\TranslationInterface $translation)
    {
        if ($translation->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $translation->setStoreId($storeId);
        }
        try {
            $this->resource->save($translation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the translation: %1', $exception->getMessage()),
                $exception
            );
        }
        return $translation;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $translation = $this->translationFactory->create();
        $translation->load($id);
        if (!$translation->getId()) {
            throw new NoSuchEntityException(__('Translation with id "%1" does not exist.', $id));
        }
        return $translation;
    }

    /**
     * @inheritdoc
     */
    public function delete(\Magefan\Translation\Api\Data\TranslationInterface $translation)
    {
        try {
            $this->resource->delete($translation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the translation: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $translation = $this->translationFactory->create();
        $translation->load($id);
        if (!$translation->getId()) {
            throw new NoSuchEntityException(__('Translation with id "%1" does not exist.', $id));
        }
        return \GuzzleHttp\json_encode($translation->getData());
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        try {
            $data = json_decode($data, true);
            $item = $this->translationFactory->create();
            $item->setData($data)->save();
            return json_encode($item->getData());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function update($id, $data)
    {
        try {
            $item = $this->translationFactory->create();
            $item->load($id);

            if (!$item->getId()) {
                return false;
            }
            $data = json_decode($data, true);
            $item->addData($data)->save();
            return json_encode($item->getData());
        } catch (\Exception $e) {
            return false;
        }
    }
}
