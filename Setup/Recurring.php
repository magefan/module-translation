<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class Recurring implements InstallSchemaInterface
{
    /**
     * @inheritdoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        $tables = [
            'magefan_blog_post',
            'magefan_blog_category',
            'magefan_blog_tag',
            'magefan_blog_author',
            'magefan_second_blog_post',
            'magefan_second_blog_category',
            'magefan_second_blog_tag',
            'magefan_second_blog_author',
        ];

        $localizationTables = [
            'magefan_blog_post_localization',
            'magefan_blog_category_localization',
            'magefan_blog_tag_localization',
            'magefan_blog_author_localization',
            'magefan_second_blog_post_localization',
            'magefan_second_blog_category_localization',
            'magefan_second_blog_tag_localization',
            'magefan_second_blog_author_localization',
        ];

        foreach ($tables as $tableName) {
            $tableName = $setup->getTable($tableName);

            if ($connection->isTableExists($tableName)
                && !$connection->tableColumnExists($tableName, 'mf_exclude_auto_translation')
            ) {
                $connection->addColumn($tableName, 'mf_exclude_auto_translation', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'comment' => 'Exclude From Auto Translation',
                    'default' => '0'
                ]);
            }
        }

        foreach ($localizationTables as $tableName) {
            $tableName = $setup->getTable($tableName);

            if ($connection->isTableExists($tableName)
                && !$connection->tableColumnExists($tableName, 'mf_exclude_auto_translation')
            ) {
                $connection->addColumn($tableName, 'mf_exclude_auto_translation', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'comment' => 'Exclude From Auto Translation',
                ]);
            }
        }

        $setup->endSetup();
    }
}
