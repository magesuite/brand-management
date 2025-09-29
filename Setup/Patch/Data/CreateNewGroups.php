<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class CreateNewGroups implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public const ATTRIBUTE_GROUP_SEARCH_ENGINE_OPTIMIZATION = 'Search Engine Optimization';
    public const ATTRIBUTE_GROUP_CONTENT_CONSTRUCTOR = 'Content Constructor';

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetup $eavSetup,
    ) {}

    public static function getDependencies(): array
    {
        return [\MageSuite\BrandManagement\Setup\Patch\Data\UpdateBrandEav::class];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $attributeSetId = (int)$this->eavSetup->getDefaultAttributeSetId(\MageSuite\BrandManagement\Model\Brands::ENTITY);

        $this->eavSetup->addAttributeGroup(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeSetId, self::ATTRIBUTE_GROUP_SEARCH_ENGINE_OPTIMIZATION, 200);
        $this->eavSetup->addAttributeGroup(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeSetId, self::ATTRIBUTE_GROUP_CONTENT_CONSTRUCTOR, 300);

        $this->addAttributesToGroup(self::ATTRIBUTE_GROUP_SEARCH_ENGINE_OPTIMIZATION, ['meta_description', 'meta_robots', 'meta_title', 'is_searchable'], $attributeSetId);
        $this->addAttributesToGroup(self::ATTRIBUTE_GROUP_CONTENT_CONSTRUCTOR, ['content_constructor_content'], $attributeSetId);
    }

    protected function addAttributesToGroup(string $groupName, array $attributes, int $attributeSetId): void
    {
        foreach ($attributes as $attributeCode) {
            $this->eavSetup->addAttributeToGroup(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeSetId, $groupName, $attributeCode);
        }
    }
}
