<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml;

use Magefan\Community\Api\GetModuleInfoInterface;
use Magefan\Community\Model\SectionFactory;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class Promo extends Template
{
    /**
     * Config section that holds the Translation product key.
     */
    private const SECTION_NAME = 'mftranslation';

    /**
     * UTM params for the upgrade links.
     */
    private const UTM_PARAMS = 'utm_source=admin&utm_medium=feature-promo-page&utm_campaign=translation';

    /**
     * @var SectionFactory
     */
    private $sectionFactory;

    /**
     * @var GetModuleInfoInterface
     */
    private $getModuleInfo;

    /**
     * @param Context $context
     * @param SectionFactory $sectionFactory
     * @param GetModuleInfoInterface $getModuleInfo
     * @param array $data
     */
    public function __construct(
        Context $context,
        SectionFactory $sectionFactory,
        GetModuleInfoInterface $getModuleInfo,
        array $data = []
    ) {
        $this->sectionFactory = $sectionFactory;
        $this->getModuleInfo = $getModuleInfo;
        parent::__construct($context, $data);
    }

    /**
     * Get the plan upgrade URL for the configured product key, or the pricing page
     *
     * @return string
     */
    public function getUpgradePlanUrl(): string
    {
        $productKey = (string)$this->sectionFactory->create(['name' => self::SECTION_NAME])->getKey();

        if ($productKey) {
            return 'https://magefan.com/mfplanupgrade/upgrade/index?product_key=' . urlencode($productKey)
                . '&' . self::UTM_PARAMS;
        }

        $productUrl = (string)$this->getModuleInfo->execute('Magefan_Translation')->getProductUrl();

        return $productUrl
            ? rtrim($productUrl, '/') . '/pricing?' . self::UTM_PARAMS
            : 'https://magefan.com/';
    }
}
