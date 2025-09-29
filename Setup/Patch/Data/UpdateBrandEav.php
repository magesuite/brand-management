<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class UpdateBrandEav implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
    ) {}

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandGroupIdentifierAttribute::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandHideTitleAttribute::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandSeoAttributes::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddContentConstructorContentAttribute::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddIsSearchableAttribute::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddMissingMetaRobotsSource::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $this->addAdditionalAttributeTable($connection);
        $this->setAttributeEntityModel($connection);
        $this->setAttributesScope($connection);
        $this->setImageAttributeType($connection);

        $connection->endSetup();
    }

    /**
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     *
     * @return void
     */
    protected function addAdditionalAttributeTable(\Magento\Framework\DB\Adapter\AdapterInterface $connection): void
    {
        $connection->update(
            $connection->getTableName('eav_entity_type'),
            [
                'additional_attribute_table' => 'brand_eav_attribute',
            ],
            ['entity_type_code = ?' => \MageSuite\BrandManagement\Model\Brands::ENTITY]
        );
    }

    protected function setAttributeEntityModel(\Magento\Framework\DB\Adapter\AdapterInterface $connection): void
    {
        $connection->update(
            $connection->getTableName('eav_entity_type'),
            [
                'attribute_model' => \MageSuite\BrandManagement\Model\ResourceModel\Eav\Attribute::class,
            ],
            ['entity_type_code = ?' => \MageSuite\BrandManagement\Model\Brands::ENTITY]
        );
    }

    protected function setAttributesScope(\Magento\Framework\DB\Adapter\AdapterInterface $connection): void
    {
        $entityTypeCode = 'brands';
        $attributeCodes = [
            'brand_additional_icon',
            'brand_group_identifier',
            'brand_icon',
            'brand_name',
            'brand_url_key',
            'content_constructor_content',
            'enabled',
            'full_description',
            'hide_headline',
            'is_featured',
            'is_searchable',
            'layout_update_xml',
            'layout_update_xml_backup',
            'meta_description',
            'meta_robots',
            'meta_title',
            'safety_regulations',
            'short_description',
            'show_in_brand_carousel',
            'sort_order',
        ];

        $entityTypeIdSelect = $connection->select();
        $entityTypeIdSelect->from($connection->getTableName('eav_entity_type'), ['entity_type_id']);
        $entityTypeIdSelect->where('entity_type_code = ?', $entityTypeCode);

        $entityTypeId = $connection->fetchOne(
            $entityTypeIdSelect
        );

        $select = $connection->select();
        $select->from(
            ['ea' => $connection->getTableName('eav_attribute')],
            [
                'attribute_id',
                new \Zend_Db_Expr('0 AS is_global'),
            ]
        );
        $select->where('ea.entity_type_id = ?', $entityTypeId);
        $select->where('ea.attribute_code IN (?)', $attributeCodes);

        $insertQuery = $connection->insertFromSelect(
            $select,
            $connection->getTableName('brand_eav_attribute'),
            ['attribute_id', 'is_global'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INSERT_IGNORE
        );

        $connection->query($insertQuery);
    }

    protected function setImageAttributeType(\Magento\Framework\DB\Adapter\AdapterInterface $connection): void
    {
        $imageAttributeCodes = [
            'brand_icon',
            'brand_additional_icon',
        ];

        $connection->update(
            $connection->getTableName('eav_attribute'),
            [
                'frontend_input' => 'image',
            ],
            [
                'attribute_code IN (?)' => $imageAttributeCodes,
            ]
        );
    }
}
