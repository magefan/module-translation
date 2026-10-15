<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Backend\Magento\Backend\Block\Widget\Grid;

use Magefan\Translation\Model\Config;

class Massaction
{
    /**
     * Email template grid page and its ajax reload
     */
    private const EMAIL_TEMPLATE_GRID_ACTIONS = [
        'adminhtml_email_template_index',
        'adminhtml_email_template_grid',
    ];

    /**
     * @var string[]
     */
    private $supportedActions = [
        'blog_post_index',
        'blog_category_index',
        'blog_tag_index',
        'blogauthor_author_index',
        'secondblog_post_index',
        'secondblog_category_index',
        'secondblog_tag_index',
        'secondblogauthor_author_index',
        'review_product_index',
        'review_product_pending',
        'adminhtml_email_template_index',
        'adminhtml_email_template_grid',
    ];

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * The email template grid has no mass actions of its own, so it checks isAvailable()
     * before anything calls getItems(), and AbstractMassaction::getCount() reads the
     * items directly - for that grid only, they are added here as well. addItem() is
     * keyed by id, so adding them twice is harmless.
     *
     * @param $subject
     * @return void
     */
    public function beforeIsAvailable($subject)
    {
        if (in_array($subject->getRequest()->getFullActionName(), self::EMAIL_TEMPLATE_GRID_ACTIONS, true)) {
            $this->beforeGetItems($subject);
        }
    }

    /**
     * @param $subject
     * @return void
     */
    public function beforeGetItems($subject)
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $fan = $subject->getRequest()->getFullActionName();

        if (!in_array($fan, $this->supportedActions)) {
            return;
        }

        $subject->addItem(
            'mass_translate_locked',
            [
                'label' => __('Auto Translate'),
                'url'   => $subject->getUrl('translation/mass_translate/locked'),
            ]
        );
    }
}
