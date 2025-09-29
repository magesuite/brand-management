<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddContentConstructorFrontendInput implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
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
        $this->eavSetup->updateattribute(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            'content_constructor_content',
            'frontend_input',
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\Modifier\Eav::CONTENT_CONSTRUCTOR_FRONTEND_INPUT
        );
    }
}
