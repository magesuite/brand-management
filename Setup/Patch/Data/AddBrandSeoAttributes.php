<?php
namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddBrandSeoAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

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
            ]
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

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [];
    }
}
