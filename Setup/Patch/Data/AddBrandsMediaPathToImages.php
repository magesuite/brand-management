<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddBrandsMediaPathToImages implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
    ) {}

    public static function getDependencies(): array
    {
        return [
            \MageSuite\BrandManagement\Setup\Patch\Data\UpdateBrandEav::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $entityTypeSelect = $connection->select()
            ->from(['et' => $connection->getTableName('eav_entity_type')], ['entity_type_id'])
            ->where('entity_type_code = ?', \MageSuite\BrandManagement\Model\Brands::ENTITY);

        $entityTypeId = $connection->fetchOne($entityTypeSelect);

        $select = $connection->select()
            ->from(['ea' => $connection->getTableName('eav_attribute')], ['attribute_id'])
            ->where('ea.entity_type_id = ?', $entityTypeId)
            ->where('ea.frontend_input = ?', 'image');

        $imageAttributeIds = $connection->fetchCol($select);

        if (empty($imageAttributeIds)) {
            return;
        }

        // If a brand image has no subdirectory in a path, then assume it is placed in a brands/ directory and add this subdirectory to the path.
        $connection->update(
            $connection->getTableName('brands_entity_varchar'),
            [
                'value' => new \Zend_Db_Expr(
                    sprintf("CONCAT('%s%s', value)", \MageSuite\BrandManagement\Controller\Adminhtml\Brand\NewImage::BRANDS_MEDIA_PATH, DIRECTORY_SEPARATOR)
                ),
            ],
            [
                'attribute_id IN (?)' => $imageAttributeIds,
                new \Zend_Db_Expr(
                    sprintf("value NOT LIKE '%%%s%%'", DIRECTORY_SEPARATOR)
                ),
                new \Zend_Db_Expr("value != ''"),
                new \Zend_Db_Expr("value IS NOT NULL"),
            ]
        );

        $connection->endSetup();
    }
}
