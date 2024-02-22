<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
$installer = $objectManager->create(\Magento\Catalog\Setup\CategorySetup::class);

$productSkus = array_map(
    function ($number) {
        return sprintf('sku%s', $number);
    },
    range(111, 120)
);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

foreach ($productSkus as $sku) {
    try {
        $product = $productRepository->get($sku);
        $productRepository->delete($product);
    } catch (\Exception $e) {
        continue;
    }
}

$brand = $brandRepository->getById(111);

if ($brand) {
    $brand->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
