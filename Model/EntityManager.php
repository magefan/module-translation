<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Model;

class EntityManager
{
    public const ALL_ID = 0;
    public const PRODUCT_ID = 1;
    public const CATEGORY_ID = 2;
    public const PAGE_ID = 3;
    public const BLOCK_ID = 4;
    public const PRODUCT_ATTRIBUTE_ID = 5;
    public const TRANSLATE_ID = 6;

    public const BLOG_POST_ID = 7;
    public const BLOG_TAG_ID = 8;
    public const BLOG_CATEGORY_ID = 9;
    public const BLOG_AUTHOR_ID = 10;

    public const SECONDBLOG_POST_ID = 11;
    public const SECONDBLOG_TAG_ID = 12;
    public const SECONDBLOG_CATEGORY_ID = 13;
    public const SECONDBLOG_AUTHOR_ID = 14;

    public const TYPE_CATEGORY = 'category';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_PAGE = 'page';
    public const TYPE_BLOCK = 'block';
    public const TYPE_PRODUCT_ATTRIBUTE = 'product_attribute';
    public const TYPE_TRANSLATE = 'search_and_translate';

    public const TYPE_BLOG_POST = 'blog_post';
    public const TYPE_BLOG_TAG = 'blog_tag';
    public const TYPE_BLOG_CATEGORY = 'blog_category';
    public const TYPE_BLOG_AUTHOR = 'blog_author';

    public const TYPE_SECONDBLOG_POST = 'secondblog_post';
    public const TYPE_SECONDBLOG_TAG = 'secondblog_tag';
    public const TYPE_SECONDBLOG_CATEGORY = 'secondblog_category';
    public const TYPE_SECONDBLOG_AUTHOR = 'secondblog_author';

    public const TYPES = [
        self::TYPE_PRODUCT => self::PRODUCT_ID,
        self::TYPE_CATEGORY => self::CATEGORY_ID,
        self::TYPE_PAGE => self::PAGE_ID,
        self::TYPE_BLOCK => self::BLOCK_ID,
        self::TYPE_PRODUCT_ATTRIBUTE => self::PRODUCT_ATTRIBUTE_ID,
        self::TYPE_TRANSLATE => self::TRANSLATE_ID,

        self::TYPE_BLOG_POST => self::BLOG_POST_ID,
        self::TYPE_BLOG_TAG => self::BLOG_TAG_ID,
        self::TYPE_BLOG_CATEGORY => self::BLOG_CATEGORY_ID,
        self::TYPE_BLOG_AUTHOR => self::BLOG_AUTHOR_ID,

        self::TYPE_SECONDBLOG_POST => self::SECONDBLOG_POST_ID,
        self::TYPE_SECONDBLOG_TAG => self::SECONDBLOG_TAG_ID,
        self::TYPE_SECONDBLOG_CATEGORY => self::SECONDBLOG_CATEGORY_ID,
        self::TYPE_SECONDBLOG_AUTHOR => self::SECONDBLOG_AUTHOR_ID
    ];

    /**
     * @var array
     */
    private $entityPool;

    /**
     * @param array $entityPool
     */
    public function __construct(
        array $entityPool
    ) {
        $this->entityPool = $entityPool;
    }

    /**
     * @param int $entityTypeId
     * @return mixed
     * @throws \Exception
     */
    public function getEntityAdapter(int $entityTypeId)
    {
        if (!isset($this->entityPool[$entityTypeId])) {
            throw new \Exception((string)__('Can not find entity type id "%1" in pool.', $entityTypeId));
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        try {
            return $objectManager->create($this->entityPool[$entityTypeId]['adapter']['class']);
        } catch (\Error $e) {
            // Adapter instantiation can fail when an optional module (e.g. Magefan_Blog)
            // is disabled or not installed and its classes are unavailable.
            // Return null so callers can safely skip this entity type.
            return null;
        }
    }
}
