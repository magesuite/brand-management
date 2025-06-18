<?php

declare(strict_types=1);

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$brandIds = [
    600,
    700,
    800,
];

foreach ($brandIds as $brandId) {
    $brand = $brandRepository->getById($brandId);

    if ($brand) {
        $brandRepository->delete($brand);
    }
}
