<?php

namespace MageSuite\BrandManagement\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetupInterface;

    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    /**
     * @var \MageSuite\BrandManagement\Setup\BrandsSetupFactory
     */
    protected $brandsSetupFactory;


    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface,
        \MageSuite\BrandManagement\Setup\BrandsSetupFactory $brandsSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;
        $this->brandsSetupFactory = $brandsSetupFactory;
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
    }

    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $brandsSetup = $this->brandsSetupFactory->create(['setup' => $setup]);
            $brandsSetup->installEntities();
        }

        if (version_compare($context->getVersion(), '0.0.4', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            if (!$eavSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'brand')) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'brand',
                    [
                        'label' => 'Brand',
                        'class' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'apply_to' => '',
                        'group' => 'General',
                        'type' => 'varchar',
                        'input' => 'multiselect',
                        'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                        'frontend' => '',
                        'source' => 'MageSuite\BrandManagement\Model\Source\BrandList',
                        'visible' => 1,
                        'required' => 0,
                        'user_defined' => 1,
                        'used_for_price_rules' => 1,
                        'position' => 2,
                        'unique' => 0,
                        'default' => '',
                        'sort_order' => 100,
                        'is_global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                        'is_required' => 0,
                        'is_configurable' => 1,
                        'is_searchable' => 1,
                        'is_visible_in_advanced_search' => 1,
                        'is_comparable' => 1,
                        'is_filterable' => 1,
                        'is_filterable_in_search' => 1,
                        'is_used_for_promo_rules' => 1,
                        'is_html_allowed_on_front' => 0,
                        'is_visible_on_front' => 1,
                        'used_in_product_listing' => 1,
                        'used_for_sort_by' => 1,
                        'system' => 0

                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '0.0.5', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'is_global',
                \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'is_searchable',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'is_visible_in_advanced_search',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'is_filterable',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'is_filterable_in_search',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'used_for_sort_by',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'used_in_product_listing',
                true
            );
        }

        if (version_compare($context->getVersion(), '0.0.8', '<')) {
            $brandsSetup = $this->brandsSetupFactory->create(['setup' => $setup]);
            $brandsSetup->installEntities();
        }

        if (version_compare($context->getVersion(), '0.0.9', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'used_for_sort_by',
                false
            );
        }

        if (version_compare($context->getVersion(), '0.0.10', '<')) {
            $brandsSetup = $this->brandsSetupFactory->create(['setup' => $setup]);
            $brandsSetup->installEntities();
        }

        if (version_compare($context->getVersion(), '0.0.11', '<')) {
            $brandsSetup = $this->brandsSetupFactory->create(['setup' => $setup]);
            $brandsSetup->installEntities();
        }

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'brand',
                'source_model',
                \MageSuite\BrandManagement\Model\Source\BrandList::class
            );
        }

        $setup->endSetup();
    }
}
