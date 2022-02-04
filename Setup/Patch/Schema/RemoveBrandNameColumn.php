<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Schema;

class RemoveBrandNameColumn implements \Magento\Framework\Setup\Patch\SchemaPatchInterface
{
    protected \Magento\Framework\Setup\SchemaSetupInterface $schemaSetup;

    public function __construct(\Magento\Framework\Setup\SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    public function apply(): void
    {
        $this->schemaSetup->startSetup();
        $this->schemaSetup->getConnection()->dropColumn(
            $this->schemaSetup->getTable('brands_entity'),
            'brand_name',
        );
        $this->schemaSetup->endSetup();
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
