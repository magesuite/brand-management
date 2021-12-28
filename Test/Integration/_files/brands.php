<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$brandRepository = $objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(600)
    ->setStoreId(0)
    ->setUrlKey('url/key')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
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
    ->setEntityId(700)
    ->setStoreId(0)
    ->setUrlKey('/mark/test.html')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
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
    ->setEntityId(40)
    ->setStoreId(0)
    ->setUrlKey('http://example.com')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png');

$brandRepository->save($brand);

$store = $objectManager->create(Magento\Store\Model\Store::class);
$store->load('test333', 'code');


$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(4040)
    ->setStoreId($store->getId())
    ->setUrlKey('urlkey3')
    ->setLayoutUpdateXml('layout update xml3')
    ->setBrandName('test_brand_name3')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage3.png')
    ->setMetaTitle('Test meta title new store')
    ->setMetaDescription('Test meta description new store')
    ->setMetaRobots('INDEX,FOLLOW');

$brandRepository->save($brand);

$brand = $objectManager->create(\MageSuite\BrandManagement\Model\Brands::class);
$brand
    ->setEntityId(4050)
    ->setStoreId($store->getId())
    ->setUrlKey('https://example.com')
    ->setLayoutUpdateXml('layout update xml3')
    ->setBrandName('test_brand_name3')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage3.png');

$brandRepository->save($brand);
