<?php
$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(700)
    ->setStoreId(1)
    ->setUrlKey('new_balance')
    ->setLayoutUpdateXml('layout update xml')
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
    ->setLayoutUpdateXml('layout update xml')
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
    ->setLayoutUpdateXml('layout update xml')
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
    ->setLayoutUpdateXml('layout update xml')
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
    ->setLayoutUpdateXml('layout update xml')
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
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('Lacoste')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setShowInBrandCarousel(0);
$brandRepository->save($brand);


