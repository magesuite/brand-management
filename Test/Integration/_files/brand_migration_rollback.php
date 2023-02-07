<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

foreach ([101,102,103] as $brandId) {
    $brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
    $brand->load($brandId);

    if ($brand->getId() > 0) {
        $brand->delete();
    }
}
