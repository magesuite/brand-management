<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class UnifyBooleanAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
    ) {}

    public static function getDependencies(): array
    {
        return [\MageSuite\BrandManagement\Setup\Patch\Data\AddBrandSeoAttributes::class];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $setup = $this->eavSetupFactory->create();
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandHideTitleAttribute::ATTRIBUTE_CODE_HIDE_HEADLINE, 'source_model');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, \MageSuite\BrandManagement\Setup\Patch\Data\AddIsSearchableAttribute::ATTRIBUTE_CODE_IS_SEARCHABLE, 'source_model');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'is_featured', 'source_model');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'enabled', 'source_model');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'show_in_brand_carousel', 'source_model');

        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'is_featured', 'frontend_input', 'boolean');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'enabled', 'frontend_input', 'boolean');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'show_in_brand_carousel', 'frontend_input', 'boolean');
    }
}
