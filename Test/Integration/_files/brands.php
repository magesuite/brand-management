<?php
$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(600)
    ->setStoreId(0)
    ->setUrlKey('url/key')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png');


$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(700)
    ->setStoreId(0)
    ->setUrlKey('/mark/test.html')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png');

$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(800)
    ->setStoreId(0)
    ->setUrlKey('http://example.com')
    ->setLayoutUpdateXml('layout update xml')
    ->setBrandName('test_brand_name')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png');

$brandRepository->save($brand);

$store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');
$store->load('test333', 'code');


$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(1000)
    ->setStoreId($store->getId())
    ->setUrlKey('urlkey3')
    ->setLayoutUpdateXml('layout update xml3')
    ->setBrandName('test_brand_name3')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage3.png');

$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(1100)
    ->setStoreId($store->getId())
    ->setUrlKey('https://example.com')
    ->setLayoutUpdateXml('layout update xml3')
    ->setBrandName('test_brand_name3')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage3.png');

$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);
