<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddMissingMetaRobotsSource implements \Magento\Framework\Setup\Patch\DataPatchInterface
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
        $setup->updateAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, 'meta_robots', 'source_model', \MageSuite\BrandManagement\Model\Brands\Attribute\Source\MetaRobots::class);
    }
}
