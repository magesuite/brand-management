<?php

namespace MageSuite\BrandManagement\Setup;

class BrandsSetup extends \Magento\Eav\Setup\EavSetup
{
    public function getDefaultEntities() {
        $brandsEntity = \MageSuite\BrandManagement\Model\Brands::ENTITY;

        $entities = [
            $brandsEntity => [
                'entity_model' => 'MageSuite\BrandManagement\Model\ResourceModel\Brands',
                'attribute_model' => 'Magento\Catalog\Model\ResourceModel\Eav\Attribute',
                'table' => $brandsEntity . '_entity',
                'entity_attribute_collection' => 'Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection',
                'attributes' => [
                    'brand_name' => [
                        'type' => 'varchar',
                        'label' => 'Brand Name',
                        'input' => 'text',
                        'frontend_class' => 'validate-length maximum-length-255',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'layout_update_xml' => [
                        'type' => 'text',
                        'label' => 'Layout Update XML',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                    ],
                    'brand_icon' => [
                        'type' => 'varchar',
                        'label' => 'Brand Icon',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'brand_additional_icon' => [
                        'type' => 'varchar',
                        'label' => 'Brand Additional Icon',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'short_description' => [
                        'type' => 'text',
                        'label' => 'Short Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'full_description' => [
                        'type' => 'text',
                        'label' => 'Full Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'brand_url_key' => [
                        'type' => 'varchar',
                        'label' => 'Url Key',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 10,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'is_featured' => [
                        'type' => 'int',
                        'label' => 'Is Featured',
                        'input' => 'select',
                        'required' => false,
                        'sort_order' => 20,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean'
                    ],
                    'enabled' => [
                        'type' => 'int',
                        'label' => 'Enabled',
                        'input' => 'select',
                        'required' => false,
                        'sort_order' => 30,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean'
                    ],
                    'show_in_brand_carousel' => [
                        'type' => 'int',
                        'label' => 'Show In Brand Carousel',
                        'input' => 'select',
                        'required' => false,
                        'sort_order' => 40,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean'
                    ]
                ]
            ]
        ];
        return $entities;
    }
}
