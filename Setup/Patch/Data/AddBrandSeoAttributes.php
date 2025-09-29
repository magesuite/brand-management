<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddBrandSeoAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {}

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $seoAttrs = [
            'meta_title' => [
                'type' => 'varchar',
                'label' => 'Meta Title',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            ],
            'meta_description' => [
                'type' => 'text',
                'label' => 'Meta Description',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 60,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            ],
            'meta_robots' => [
                'type' => 'varchar',
                'label' => 'Meta Robots',
                'input' => 'select',
                'required' => false,
                'sort_order' => 70,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            ],
        ];

        foreach ($seoAttrs as $seoAttrCode => $seoAttrData) {
            $eavSetup->addAttribute(
                \MageSuite\BrandManagement\Model\Brands::ENTITY,
                $seoAttrCode,
                $seoAttrData
            );
        }

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
