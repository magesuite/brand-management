<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class RestructureBrandEditForm implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public const ATTRIBUTE_GROUP_GENERAL = 'General';
    public const ATTRIBUTE_GROUP_CONTENT = 'Content';

    protected const ATTRIBUTE_GROUP_CONTENT_SORT_ORDER = 100;

    protected const GROUPED_ATTRIBUTES = [
        self::ATTRIBUTE_GROUP_GENERAL => [
            'enabled' => 10,
            'brand_name' => 20,
            'brand_url_key' => 30,
            'is_featured' => 40,
            'sort_order' => 50,
            'brand_group_identifier' => 60,
            'show_in_brand_carousel' => 70,
        ],
        self::ATTRIBUTE_GROUP_CONTENT => [
            'short_description' => 10,
            'full_description' => 20,
            'safety_regulations' => 30,
            'hide_headline' => 40,
            'brand_icon' => 50,
            'brand_additional_icon' => 60,
        ],
    ];

    protected const REQUIRED_ATTRIBUTES = [
        'brand_name',
        'brand_url_key',
    ];

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
    ) {}

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\UpdateBrandEav::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\CreateNewGroups::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandSortOrderAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeSetId = (int)$eavSetup->getDefaultAttributeSetId(\MageSuite\BrandManagement\Model\Brands::ENTITY);

        $eavSetup->addAttributeGroup(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            $attributeSetId, self::ATTRIBUTE_GROUP_CONTENT,
            self::ATTRIBUTE_GROUP_CONTENT_SORT_ORDER
        );

        $this->assignAttributesToGroups($eavSetup, $attributeSetId);
        $this->markAttributesAsRequired($eavSetup);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    protected function assignAttributesToGroups(\Magento\Eav\Setup\EavSetup $eavSetup, int $attributeSetId): void
    {
        foreach (self::GROUPED_ATTRIBUTES as $groupName => $attributes) {
            foreach ($attributes as $attributeCode => $sortOrder) {
                $eavSetup->addAttributeToGroup(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeSetId, $groupName, $attributeCode, $sortOrder);
            }
        }
    }

    protected function markAttributesAsRequired(\Magento\Eav\Setup\EavSetup $eavSetup): void
    {
        foreach (self::REQUIRED_ATTRIBUTES as $attributeCode) {
            $eavSetup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeCode, 'is_required', 1);
        }
    }
}
