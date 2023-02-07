<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class MigrateContentConfigToJsonFormat implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    protected \MageSuite\BrandManagement\Service\Upgrade\Migration $migration;

    public function __construct(\MageSuite\BrandManagement\Service\Upgrade\Migration $migration)
    {
        $this->migration = $migration;
    }

    public function apply()
    {
        $this->migration->transferOldXmlValuesToNewJsonFields();
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
