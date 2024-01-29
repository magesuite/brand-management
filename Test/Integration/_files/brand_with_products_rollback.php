<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$productSkus = ['sku111', 'sku112', 'sku113', 'sku114', 'sku115'];

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

foreach ($productSkus as $sku) {
    $product = $productRepository->get($sku, false, null, true);
    $productRepository->delete($product);
}

$brand = $brandRepository->getById(111);
$brand->delete();

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
