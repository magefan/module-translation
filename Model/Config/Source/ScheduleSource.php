<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Source;

use Magefan\Translation\Model\EntityManager;

class ScheduleSource implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var null
     */
    private $labelsMap = null;

    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        $result = [
            ['value' => EntityManager::ALL_ID,                  'label' => __('ALL')],
            ['value' => EntityManager::PRODUCT_ID,              'label' => __('Product')],
            ['value' => EntityManager::CATEGORY_ID,             'label' => __('Category')],
            ['value' => EntityManager::PAGE_ID,                 'label' => __('CMS Page')],
            ['value' => EntityManager::BLOCK_ID,                'label' => __('CMS Block')],
            ['value' => EntityManager::PRODUCT_ATTRIBUTE_ID,    'label' => __('Product Attribute')],

            ['value' => EntityManager::BLOG_POST_ID,            'label' => __('Magefan Blog Post')],
            ['value' => EntityManager::BLOG_TAG_ID,             'label' => __('Magefan Blog Tag')],
            ['value' => EntityManager::BLOG_CATEGORY_ID,        'label' => __('Magefan Blog Category')],
            ['value' => EntityManager::BLOG_AUTHOR_ID,          'label' => __('Magefan Blog Author')],

            ['value' => EntityManager::SECONDBLOG_POST_ID,     'label' => __('Magefan Second Blog Post')],
            ['value' => EntityManager::SECONDBLOG_TAG_ID,      'label' => __('Magefan Second Blog Tag')],
            ['value' => EntityManager::SECONDBLOG_CATEGORY_ID, 'label' => __('Magefan Second Blog Category')],
            ['value' => EntityManager::SECONDBLOG_AUTHOR_ID,   'label' => __('Magefan Second Blog Author')],

            ['value' => EntityManager::TRANSLATE_ID,            'label' => __('Phrases from Search And Translate')]
        ];

        return $result;
    }

    /**
     * @param int $id
     * @return string
     */
    public function getEntityLabelById(int $id): string
    {
        if (null === $this->labelsMap) {
            foreach ($this->toOptionArray() as $item) {
                $this->labelsMap[$item['value']] = (string)$item['label'];
            }
        }

        return $this->labelsMap[$id] ?? '';
    }

    /**
     * @return array
     */
    public function getEntityTypeToLabelOptions():array
    {
        $options = $this->toOptionArray();
        $result = [];

        foreach (EntityManager::TYPES as $type => $typeId) {
            foreach ($options as $option) {
                if ($option['value'] === $typeId) {
                    $result[$type] = (string)$option['label'];
                }
            }
        }

        return $result;
    }
}
