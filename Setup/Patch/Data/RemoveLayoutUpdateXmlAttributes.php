<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class RemoveLayoutUpdateXmlAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    protected const ATTRIBUTE_CODES = [
        'layout_update_xml',
        \MageSuite\BrandManagement\Setup\Patch\Data\AddContentConstructorContentAttribute::BACKUP_ATTRIBUTE_CODE,
    ];

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
    ) {}

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\MigrateContentConfigToJsonFormat::class,
            \MageSuite\BrandManagement\Setup\Patch\Data\RestructureBrandEditForm::class,
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

        foreach (self::ATTRIBUTE_CODES as $attributeCode) {
            $eavSetup->removeAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, $attributeCode);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
