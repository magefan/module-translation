<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Block\Adminhtml\Translation\Edit;

use Magento\Backend\Block\Widget\Context;
use Magefan\Translation\Api\TranslationRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var TranslationRepositoryInterface
     */
    protected $translationRepository;

    /**
     * GenericButton constructor.
     *
     * @param Context $context
     * @param TranslationRepositoryInterface $translationRepository
     */
    public function __construct(
        Context $context,
        TranslationRepositoryInterface $translationRepository
    ) {
        $this->context = $context;
        $this->translationRepository = $translationRepository;
    }

    /**
     * Get translation id.
     *
     * @return null
     */
    public function getTranslationId()
    {
        try {
            return $this->translationRepository->getById(
                $this->context->getRequest()->getParam('key_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get url.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
