<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(10)
    ->setStoreId(0)
    ->setUrlKey('brand_with_identifier')
    ->setBrandName('brand_with_identifier')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandGroupIdentifier('special-identifier');

$brandRepository->save($brand);
