<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$productSkus = ['sku111', 'sku112', 'sku113', 'sku114', 'sku115'];

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

foreach ($productSkus as $sku) {
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
        ->setBrand(111)
        ->save();
}
