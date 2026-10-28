<?php

declare(strict_types=1);

$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(700)
    ->setStoreId(1)
    ->setUrlKey('new_balance')
    ->setBrandName('New Balance')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(701)
    ->setStoreId(1)
    ->setUrlKey('nike')
    ->setBrandName('nike')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(702)
    ->setStoreId(1)
    ->setUrlKey('air_jordan')
    ->setBrandName('Air Jordan')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(703)
    ->setStoreId(1)
    ->setUrlKey('admiral_sportswear')
    ->setBrandName('Admiral Sportswear')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(704)
    ->setStoreId(1)
    ->setUrlKey('adidas')
    ->setBrandName('Adidas')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(705)
    ->setStoreId(1)
    ->setUrlKey('lacoste')
    ->setBrandName('Lacoste')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(706)
    ->setStoreId(1)
    ->setUrlKey('loreal')
    ->setBrandName('é Loreal with special char as first letter')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);
