<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
$brandKey = 'brand_with_identifier';

$brands = $brandRepository->getAllBrands(\MageSuite\BrandManagement\Model\BrandsRepository::DEFAULT_STORE_ID);

foreach ($brands as $brand) {
    $brandRepository->delete($brand);
}
