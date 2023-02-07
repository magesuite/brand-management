<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(101)
    ->setStoreId(0)
    ->setUrlKey('url/key')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('brand-600')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png')
    ->setMetaTitle('Test meta title')
    ->setMetaDescription('Test meta description')
    ->setMetaRobots('NOINDEX,NOFOLLOW');
$brandRepository->save($brand);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(102)
    ->setStoreId(0)
    ->setUrlKey('/mark/test.html')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('brand-700')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png')
    ->setMetaTitle('Test meta title 2')
    ->setMetaDescription('Test meta description 2')
    ->setMetaRobots('NOINDEX,NOFOLLOW');

$brandRepository->save($brand);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(103)
    ->setStoreId(0)
    ->setUrlKey('http://example.com')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('brand-40')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png');

$brandRepository->save($brand);