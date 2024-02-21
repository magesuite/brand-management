<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
$attribute = $objectManager->create(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class);
$productAttributeRepository = $objectManager->create(\Magento\Catalog\Api\ProductAttributeRepositoryInterface::class);
$installer = $objectManager->create(\Magento\Catalog\Setup\CategorySetup::class);

$colorAttribute = $attribute->loadByCode(\Magento\Catalog\Model\Product::ENTITY, 'color');

$colorAttribute
    ->setOptions([
        'red' => new \Magento\Framework\DataObject(['value' => 'red', 'label' => 'Red']),
        'green' => new \Magento\Framework\DataObject(['value' => 'green', 'label' => 'Green']),
        'blue' => new \Magento\Framework\DataObject(['value' => 'blue', 'label' => 'Blue'])
    ])
    ->setData('is_filterable', 1)
    ->setData('is_filterable_in_search', 1)
    ->setData('is_visible_on_front', 1)
    ->setData('is_used_in_product_listing', 1)
    ->setData('facet_min_coverage_rate', 10);


$productAttributeRepository->save($attribute);
$installer->addAttributeToGroup('catalog_product', 'Default', 'General', $attribute->getId());

$colorNameToIdMap = [];
foreach ($colorAttribute->getSource()->getAllOptions() as $option) {
    $colorNameToIdMap[$option['label']] = $option['value'];
}

$productSkus = [
    'sku111' => [
        'color' => $colorNameToIdMap['Red'],
    ],
    'sku112' => [
        'color' => $colorNameToIdMap['Red'],
    ],
    'sku113' => [
        'color' => $colorNameToIdMap['Green'],
    ],
    'sku114' => [
        'color' => $colorNameToIdMap['Red'],
    ],
    'sku115' => [
        'color' => $colorNameToIdMap['Red'],
    ],
    'sku116' => [
        'color' => $colorNameToIdMap['Green'],
    ],
    'sku117' => [
        'color' => $colorNameToIdMap['Red'],
    ],
    'sku118' => [
        'color' => $colorNameToIdMap['Blue'],
    ],
    'sku119' => [
        'color' => $colorNameToIdMap['Green'],
    ],
    'sku120' => [
        'color' => $colorNameToIdMap['Red'],
    ],

];

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(111)
    ->setStoreId(0)
    ->setUrlKey('brand_url_key')
    ->setLayoutUpdateXml('')
    ->setBrandName('Test brand')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('')
    ->setBrandAdditionalIcon('');
$brandRepository->save($brand);

foreach ($productSkus as $sku => $productData) {
    $product = $objectManager->create(\Magento\Catalog\Model\Product::class);

    $product->isObjectNew(true);
    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setAttributeSetId(4)
        ->setName(sprintf('Product %s', $sku))
        ->setSku($sku)
        ->setTaxClassId('none')
        ->setDescription('description')
        ->setPrice(10)
        ->setWeight(1)
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setWebsiteIds([1])
        ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->setBrand(111);

    foreach ($productData as $attributeCode => $attributeValue) {
        $product->setData($attributeCode, $attributeValue);
    }

    $product->save();
}
