<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class RemoveLayoutUpdateXmlBackupAttribute implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'layout_update_xml_backup';

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply(): self
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(\MageSuite\BrandManagement\Model\Brands::ENTITY, self::ATTRIBUTE_CODE);

        return $this;
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\AddContentConstructorContentAttribute::class
        ];
    }
}