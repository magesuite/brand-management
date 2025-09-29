<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class MigrateContentConfigToJsonFormat implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(protected \MageSuite\BrandManagement\Service\Upgrade\Migration $migration) {}

    public function apply(): void
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
            \MageSuite\BrandManagement\Setup\Patch\Data\AddContentConstructorContentAttribute::class,
        ];
    }
}
