<?php

namespace MageSuite\BrandManagement\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            if (!$installer->getConnection()->isTableExists($installer->getTable('brands_entity'))) {
                /**
                 * Create table 'brands_entity'
                 */
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('brands_entity')
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID'
                )
                    ->addColumn(
                        'brand_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        64,
                        [],
                        'Brand Name'
                    )
                    ->addColumn(
                        'layout_update_xml',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        64,
                        [],
                        'Layout Update XML'
                    )
                    ->addColumn(
                        'brand_icon',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        64,
                        [],
                        'Brand Icon'
                    )
                    ->addColumn(
                        'brand_url_key',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        64,
                        [],
                        'Brand Url Key'
                    )
                    ->addColumn(
                        'is_featured',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Is Featured'
                    )
                    ->addColumn(
                        'enabled',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Enabled'
                    )->setComment(
                        'Brands Entity'
                    );
                $installer->getConnection()->createTable($table);

                /**
                 * Create table 'eav_entity_int'
                 */
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('brands_entity_int')
                )->addColumn(
                    'value_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Value Id'
                )->addColumn(
                    'attribute_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Attribute Id'
                )->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Store Id'
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Entity Id'
                )->addColumn(
                    'value',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Attribute Value'
                )->addIndex(
                    $setup->getIdxName(
                        'brands_entity_int',
                        ['entity_id', 'attribute_id', 'store_id'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['entity_id', 'attribute_id', 'store_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_int', ['attribute_id']),
                        ['attribute_id']
                    )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_int', ['store_id']),
                        ['store_id']
                    )
                    ->addForeignKey(
                        $setup->getFkName('brands_entity_int', 'attribute_id', 'eav_attribute', 'attribute_id'),
                        'attribute_id',
                        $setup->getTable('eav_attribute'),
                        'attribute_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName('brands_entity_int', 'entity_id', 'brands_entity', 'entity_id'),
                        'entity_id',
                        $setup->getTable('brands_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName('brands_entity_int', 'store_id', 'store', 'store_id'),
                        'store_id',
                        $setup->getTable('store'),
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Brands Entity Value Prefix'
                    );
                $installer->getConnection()->createTable($table);

                /**
                 * Create table 'eav_entity_text'
                 */
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('brands_entity_text')
                )->addColumn(
                    'value_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Value Id'
                )->addColumn(
                    'attribute_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Attribute Id'
                )->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Store Id'
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Entity Id'
                )->addColumn(
                    'value',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => false],
                    'Attribute Value'
                )->addIndex(
                    $setup->getIdxName(
                        'brands_entity_text',
                        ['entity_id', 'attribute_id', 'store_id'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['entity_id', 'attribute_id', 'store_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_text', ['attribute_id']),
                        ['attribute_id']
                    )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_text', ['store_id']),
                        ['store_id']
                    )
                    ->addForeignKey(
                        $setup->getFkName(
                            'brands_entity_text',
                            'attribute_id',
                            'eav_attribute',
                            'attribute_id'
                        ),
                        'attribute_id',
                        $setup->getTable('eav_attribute'),
                        'attribute_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName(
                            'brands_entity_text',
                            'entity_id',
                            'brands_entity',
                            'entity_id'
                        ),
                        'entity_id',
                        $setup->getTable('brands_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName('brands_entity_text', 'store_id', 'store', 'store_id'),
                        'store_id',
                        $setup->getTable('store'),
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )->setComment(
                        'Brands Entity Value Prefix'
                    );
                $installer->getConnection()->createTable($table);

                /**
                 * Create table 'eav_entity_varchar'
                 */
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('brands_entity_varchar')
                )->addColumn(
                    'value_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Value Id'
                )->addColumn(
                    'attribute_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Attribute Id'
                )->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Store Id'
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Entity Id'
                )->addColumn(
                    'value',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Attribute Value'
                )->addIndex(
                    $setup->getIdxName(
                        'brands_entity_varchar',
                        ['entity_id', 'attribute_id', 'store_id'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['entity_id', 'attribute_id', 'store_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_varchar', ['attribute_id']),
                        ['attribute_id']
                    )
                    ->addIndex(
                        $setup->getIdxName('brands_entity_varchar', ['store_id']),
                        ['store_id']
                    )
                    ->addForeignKey(
                        $setup->getFkName(
                            'brands_entity_varchar',
                            'attribute_id',
                            'eav_attribute',
                            'attribute_id'
                        ),
                        'attribute_id',
                        $setup->getTable('eav_attribute'),
                        'attribute_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName(
                            'brands_entity_varchar',
                            'entity_id',
                            'brands_entity',
                            'entity_id'
                        ),
                        'entity_id',
                        $setup->getTable('brands_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName('brands_entity_varchar', 'store_id', 'store', 'store_id'),
                        'store_id',
                        $setup->getTable('store'),
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Brands Entity Value Prefix'
                    );
                $installer->getConnection()->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '0.0.8', '<')) {
            if ($installer->getConnection()->tableColumnExists($installer->getTable('brands_entity'), 'show_in_brand_carousel') == false) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('brands_entity'),
                    'show_in_brand_carousel',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'length' => 10,
                        'nullable' => true,
                        'comment' => 'Show In Brand Carousel'
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '0.0.11', '<')) {
            if ($installer->getConnection()->tableColumnExists($installer->getTable('brands_entity'), 'short_description') == false) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('brands_entity'),
                    'short_description',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Short Description'
                    ]
                );
            }

            if ($installer->getConnection()->tableColumnExists($installer->getTable('brands_entity'), 'full_description') == false) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('brands_entity'),
                    'full_description',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Full Description'
                    ]
                );
            }
        }

        $installer->endSetup();
    }
}
