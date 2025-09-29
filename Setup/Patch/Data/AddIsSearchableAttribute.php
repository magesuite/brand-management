<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddIsSearchableAttribute implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public const ATTRIBUTE_CODE_IS_SEARCHABLE = 'is_searchable';

    protected ?\Magento\Eav\Setup\EavSetup $eavSetup = null;

    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands $resourceModel,
    ) {
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        $this->addAttribute();
        $this->updateEntityValues();
        $this->moduleDataSetup->endSetup();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addAttribute(): void
    {
        $eavSetup = $this->getEavSetup();
        $eavSetup->addAttribute(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            self::ATTRIBUTE_CODE_IS_SEARCHABLE,
            [
                'label' => 'Is Searchable',
                'type' => 'int',
                'input' => 'boolean',
                'required' => false,
                'sort_order' => 200,
                'default' => 1,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            ]
        );
    }

    protected function updateEntityValues(): void
    {
        $eavSetup = $this->getEavSetup();
        $attributeId = $eavSetup->getAttributeId(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            self::ATTRIBUTE_CODE_IS_SEARCHABLE
        );
        $attributeTable = $eavSetup->getAttributeTable(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            self::ATTRIBUTE_CODE_IS_SEARCHABLE
        );
        $linkField = $this->resourceModel->getLinkField();

        $connection = $this->moduleDataSetup->getConnection();
        $select = $connection->select();
        $select->from(['et' => $this->resourceModel->getEntityTable()], []);

        $columns = [
            $linkField => sprintf('et.%s', $linkField),
            'attribute_id' => new \Zend_Db_Expr($attributeId),
            'store_id' => new \Zend_Db_Expr(\Magento\Store\Model\Store::DEFAULT_STORE_ID),
            'value' => new \Zend_Db_Expr(1),
        ];
        $select->columns($columns);

        $connection->query($connection->insertFromSelect(
            $select,
            $attributeTable,
            array_keys($columns),
            \Magento\Framework\DB\Adapter\AdapterInterface::INSERT_ON_DUPLICATE
        ));
    }

    protected function getEavSetup(): \Magento\Eav\Setup\EavSetup
    {
        if ($this->eavSetup === null) {
            $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        }

        return $this->eavSetup;
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
