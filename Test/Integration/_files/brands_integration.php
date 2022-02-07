<?php
$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(600)
    ->setStoreId(1)
    ->setUrlKey('urlkey')
    ->setBrandName('test_brand_name')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png')
    ->setShowInBrandCarousel(0)
    ->setShortDescription('short description 1')
    ->setFullDescription('full description 1')
    ->setMetaTitle('Test meta title')
    ->setMetaDescription('Test meta description')
    ->setMetaRobots('NOINDEX,NOFOLLOW');

$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);

$brand = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Model\Brands');
$brand
    ->setEntityId(700)
    ->setStoreId(0)
    ->setUrlKey('urlkey2')
    ->setBrandName('test_brand_name2')
    ->setEnabled(1)
    ->setIsFeatured(1)
    ->setBrandIcon('testimage.png')
    ->setBrandAdditionalIcon('testimage_additional.png')
    ->setShowInBrandCarousel(0)
    ->setShortDescription('short description 2')
    ->setFullDescription('full description 2')
    ->setMetaTitle('Test meta title 2')
    ->setMetaDescription('Test meta description 2')
    ->setMetaRobots('INDEX,FOLLOW');


$brandRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');
$brandRepository->save($brand);
