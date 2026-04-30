<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types = 1);

namespace Magefan\Translation\Model;

use Magefan\Translation\Model\Config;
use Magefan\Translation\Model\EntityManager;
use Magento\Catalog\Api\CategoryAttributeRepositoryInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Module\Manager as ModuleManager;

class TranslatableData
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var CategoryAttributeRepositoryInterface
     */
    private $categoryAttributeRepository;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepository;

    /**
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;

    /**
     * @var \Magefan\Translation\Model\Config
     */
    private $config;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param CategoryAttributeRepositoryInterface $categoryAttributeRepository
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param \Magefan\Translation\Model\Config $config
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        CategoryAttributeRepositoryInterface $categoryAttributeRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        FilterGroupBuilder $filterGroupBuilder,
        Config $config,
        ModuleManager $moduleManager
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->categoryAttributeRepository = $categoryAttributeRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->config = $config;
        $this->moduleManager = $moduleManager;
    }

    public function getList(?string $type = null): array
    {
        $data = [];

        switch ($type) {
            case EntityManager::TYPE_CATEGORY:
                $data[EntityManager::TYPE_CATEGORY] = $this->getCatalogAttributes($this->categoryAttributeRepository);
                break;
            case EntityManager::TYPE_PRODUCT:
                $data[EntityManager::TYPE_PRODUCT] = $this->getCatalogAttributes($this->productAttributeRepository);
                $data[EntityManager::TYPE_PRODUCT][] = ['label' => __('Customizable Options'), 'code' => 'customizable_options'];
                break;
            case EntityManager::TYPE_PAGE:
                $data[EntityManager::TYPE_PAGE] = $this->getCmsAttributesByType(EntityManager::TYPE_PAGE);
                break;
            case EntityManager::TYPE_BLOCK:
                $data[EntityManager::TYPE_BLOCK] = $this->getCmsAttributesByType(EntityManager::TYPE_BLOCK);
                break;
            case EntityManager::TYPE_PRODUCT_ATTRIBUTE:
                $data[EntityManager::TYPE_PRODUCT_ATTRIBUTE] = [
                    ['label' => __('Attribute Code'), 'code' => 'attribute_code', 'type' => 'text'],
                    ['label' => __('Label'),           'code' => 'frontend_label', 'type' => 'text'],
                ];
                break;
            case EntityManager::TYPE_TRANSLATE:
                $data[EntityManager::TYPE_TRANSLATE] = [
                    ['label' => __('String'), 'code' => 'string', 'type' => 'text'],
                ];
                break;
            case EntityManager::TYPE_BLOG_POST:
                $data[EntityManager::TYPE_BLOG_POST] = $this->getBlogPostFields();
                break;
            case EntityManager::TYPE_BLOG_TAG:
                $data[EntityManager::TYPE_BLOG_TAG] = $this->getBlogTagFields();
                break;
            case EntityManager::TYPE_BLOG_CATEGORY:
                $data[EntityManager::TYPE_BLOG_CATEGORY] = $this->getBlogCategoryFields();
                break;
            case EntityManager::TYPE_BLOG_AUTHOR:
                $data[EntityManager::TYPE_BLOG_AUTHOR] = $this->getBlogAuthorFields();
                break;

            case EntityManager::TYPE_SECONDBLOG_POST:
                $data[EntityManager::TYPE_SECONDBLOG_POST] = $this->getBlogPostFields();
                break;
            case EntityManager::TYPE_SECONDBLOG_TAG:
                $data[EntityManager::TYPE_SECONDBLOG_TAG] = $this->getBlogTagFields();
                break;
            case EntityManager::TYPE_SECONDBLOG_CATEGORY:
                $data[EntityManager::TYPE_SECONDBLOG_CATEGORY] = $this->getBlogCategoryFields();
                break;
            case EntityManager::TYPE_SECONDBLOG_AUTHOR:
                $data[EntityManager::TYPE_SECONDBLOG_AUTHOR] = $this->getBlogAuthorFields();
                break;

            default:
                // If no type or unrecognized type is passed, return all
                $data[EntityManager::TYPE_CATEGORY] = $this->getCatalogAttributes($this->categoryAttributeRepository);
                $data[EntityManager::TYPE_PRODUCT] = $this->getCatalogAttributes($this->productAttributeRepository);
                $data[EntityManager::TYPE_PRODUCT][] = ['label' => __('Customizable Options'), 'code' => 'customizable_options'];
                $data[EntityManager::TYPE_PAGE] = $this->getCmsAttributesByType(EntityManager::TYPE_PAGE);
                $data[EntityManager::TYPE_BLOCK] = $this->getCmsAttributesByType(EntityManager::TYPE_BLOCK);

                $data[EntityManager::TYPE_BLOG_POST] = $this->getBlogPostFields();
                $data[EntityManager::TYPE_BLOG_TAG] = $this->getBlogTagFields();
                $data[EntityManager::TYPE_BLOG_CATEGORY] = $this->getBlogCategoryFields();
                $data[EntityManager::TYPE_BLOG_AUTHOR] = $this->getBlogAuthorFields();

                if ($this->moduleManager->isEnabled('Magefan_SecondBlog')) {
                    $data[EntityManager::TYPE_SECONDBLOG_POST] = $this->getBlogPostFields();
                    $data[EntityManager::TYPE_SECONDBLOG_TAG] = $this->getBlogTagFields();
                    $data[EntityManager::TYPE_SECONDBLOG_CATEGORY] = $this->getBlogCategoryFields();
                    $data[EntityManager::TYPE_SECONDBLOG_AUTHOR] = $this->getBlogAuthorFields();
                }

                break;
        }

        return $data;
    }

    /**
     * Returns [{code, label}] for every field of the given type that is not excluded.
     * Returns [] for types without per-field metadata (product_attribute, search_and_translate).
     *
     * @param string $entityType
     * @return array
     */
    public function getAllowedFieldList(string $entityType): array
    {
        $typeData = $this->getList($entityType);

        if (!isset($typeData[$entityType])) {
            return [];
        }

        $allowedCodes = array_flip(
            $this->removeNotAllowedFields(
                array_column($typeData[$entityType], 'code'),
                $entityType
            )
        );

        $fields = [];
        foreach ($typeData[$entityType] as $item) {
            if (isset($allowedCodes[$item['code']])) {
                $fields[] = [
                    'code'  => $item['code'],
                    'label' => (string)($item['label'] ?? $item['code']),
                ];
            }
        }

        return $fields;
    }

    /**
     * @param string $entityType
     * @return array
     */
    public function getAllowedFields(string $entityType): array
    {
        return array_column($this->getAllowedFieldList($entityType), 'code');
    }

    /**
     * @param array $fields
     * @param string $entityType
     * @return array
     */
    public function removeNotAllowedFields(array $fields, string $entityType): array
    {
        $skippedFields = $this->config->getSkippedFields();

        foreach ($fields as $key => $attributeCode) {
            $attributeKey = $entityType . '/' . $attributeCode;

            if (isset($skippedFields[$attributeKey])) {
                unset($fields[$key]);
            }
        }

        return $fields;
    }

    /**
     * @param $repository
     * @return array
     */
    private function getCatalogAttributes($repository): array
    {
        $inputFilter = $this->filterBuilder
            ->setField('frontend_input')
            ->setConditionType('in')
            ->setValue(['text', 'textarea'])
            ->create();

        $typeFilter = $this->filterBuilder
            ->setField('backend_type')
            ->setConditionType('in')
            ->setValue(['varchar', 'text'])
            ->create();

        $visibilityFilter = $this->filterBuilder
            ->setField('is_visible')
            ->setConditionType('eq')
            ->setValue(1)
            ->create();

        $inputGroup = $this->filterGroupBuilder->addFilter($inputFilter)->create();
        $typeGroup = $this->filterGroupBuilder->addFilter($typeFilter)->create();
        $visibilityGroup = $this->filterGroupBuilder->addFilter($visibilityFilter)->create();

        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups([$inputGroup, $typeGroup, $visibilityGroup])->create();
        $attributesList = $repository->getList($searchCriteria)->getItems();

        $attributes = [];

        foreach ($attributesList as $attribute) {
            if ($attribute->getIsGlobal()) {
                continue;
            }

            $type = ($attribute->getFrontendInput() == 'textarea') ? 'html' : 'text';

            $attributes[] = [
                'label' => $attribute->getDefaultFrontendLabel(),
                'code' => $attribute->getAttributeCode(),
                'type' => $type
            ];
        }

        return $attributes;
    }

    /**
     * @param $entityType
     * @return array[]
     */
    private function getCmsAttributesByType($entityType): array
    {
        $cmsAttributes = [
            EntityManager::TYPE_PAGE => [
                ['label' => __('Title'), 'code' => 'title', 'type' => 'text'],
                ['label' => __('Content Heading'), 'code' => 'content_heading', 'type' => 'text'],
                ['label' => __('Content'), 'code' => 'content', 'type' => 'html'],
                ['label' => __('Meta Title'), 'code' => 'meta_title', 'type' => 'html'],
                ['label' => __('Meta Description'), 'code' => 'meta_description', 'type' => 'html'],
                ['label' => __('Meta Keywords'), 'code' => 'meta_keywords', 'type' => 'html']
            ],
            EntityManager::TYPE_BLOCK => [
                ['label' => __('Title'), 'code' => 'title', 'type' => 'text'],
                ['label' => __('Content'), 'code' => 'content', 'type' => 'html']
            ]
        ];

        return $cmsAttributes[$entityType];
    }

    /**
     * @return array[]
     */
    private function getBlogPostFields(): array
    {
        return [
            ['label' => __('Title'), 'code' => 'title'],
            ['label' => __('Meta Title'), 'code' => 'meta_title'],
            ['label' => __('Meta Keywords'), 'code' => 'meta_keywords'],
            ['label' => __('Meta Description'), 'code' => 'meta_description'],
            ['label' => __('OG Title'), 'code' => 'og_title'],
            ['label' => __('OG Description'), 'code' => 'og_description'],
            ['label' => __('Content Heading'), 'code' => 'content_heading'],
            ['label' => __('Content'), 'code' => 'content'],
            ['label' => __('Featured Image Alt'), 'code' => 'featured_img_alt'],
            ['label' => __('Short Content'), 'code' => 'short_content']
        ];
    }

    /**
     * @return array[]
     */
    private function getBlogTagFields(): array
    {
        return [
            ['label' => __('Title'), 'code' => 'title'],
            ['label' => __('Meta Title'), 'code' => 'meta_title'],
            ['label' => __('Meta Keywords'), 'code' => 'meta_keywords'],
            ['label' => __('Meta Description'), 'code' => 'meta_description'],
            ['label' => __('Content'), 'code' => 'content']
        ];
    }

    /**
     * @return array[]
     */
    private function getBlogCategoryFields(): array
    {
        return [
            ['label' => __('Title'), 'code' => 'title'],
            ['label' => __('Meta Title'), 'code' => 'meta_title'],
            ['label' => __('Meta Keywords'), 'code' => 'meta_keywords'],
            ['label' => __('Meta Description'), 'code' => 'meta_description'],
            ['label' => __('Content Heading'), 'code' => 'content_heading'],
            ['label' => __('Content'), 'code' => 'content'],
            ['label' => __('Category Image Alt'), 'code' => 'category_img_alt'],
            ['label' => __('Bottom Content'), 'code' => 'bottom_content']
        ];
    }

    /**
     * @return array[]
     */
    private function getBlogAuthorFields(): array
    {
        return [
            ['label' => __('First Name'), 'code' => 'firstname'],
            ['label' => __('Last Name'), 'code' => 'lastname'],
            ['label' => __('Role'), 'code' => 'role'],
            ['label' => __('Meta Title'), 'code' => 'meta_title'],
            ['label' => __('Meta Keywords'), 'code' => 'meta_keywords'],
            ['label' => __('Meta Description'), 'code' => 'meta_description'],
            ['label' => __('Content'), 'code' => 'content'],
            ['label' => __('Short Content'), 'code' => 'short_content']
        ];
    }
}
