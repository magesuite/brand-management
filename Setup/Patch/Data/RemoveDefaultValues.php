<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

/**
 * Default values of eav attributes must be removed in case if attribute can be set per store,
 * because in such case selecting 'Use default value' checkbox it will apply default value of attribute to specific store instead of value from default store.
 */
class RemoveDefaultValues implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
    ) {}

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandHideTitleAttribute::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\AddIsSearchableAttribute::class
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $setup = $this->eavSetupFactory->create();
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, \MageSuite\BrandManagement\Setup\Patch\Data\AddBrandHideTitleAttribute::ATTRIBUTE_CODE_HIDE_HEADLINE, 'default_value');
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, \MageSuite\BrandManagement\Setup\Patch\Data\AddIsSearchableAttribute::ATTRIBUTE_CODE_IS_SEARCHABLE, 'default_value');
    }
}
