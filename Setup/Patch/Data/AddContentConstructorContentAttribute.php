<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddContentConstructorContentAttribute implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    const ATTRIBUTE_CODE = 'content_constructor_content';

    protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup;

    protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory;

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'text',
                'label' => 'Content Constructor Content',
                'input' => 'text',
                'required' => false,
                'sort_order' => 80,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL
            ]
        );

        $this->moduleDataSetup->endSetup();
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
