<?php
$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$resolver->requireDataFixture('MageSuite_BrandManagement::Test/Integration/_files/store.php');
$resolver->requireDataFixture('MageSuite_BrandManagement::Test/Integration/_files/brands.php');

/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setName('Simple Product1')
    ->setSku('simple1')
    ->setTaxClassId('none')
    ->setDescription('description uniqueword')
    ->setShortDescription('short description')
    ->setOptionsContainer('container1')
    ->setMsrpDisplayActualPriceType(\Magento\Msrp\Model\Product\Attribute\Source\Type::TYPE_IN_CART)
    ->setPrice(10)
    ->setWeight(1)
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setCategoryIds([])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setSpecialPrice('5.99')
    ->setBrand('40')
    ->save();

$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setName('Simple Product2')
    ->setSku('simple2')
    ->setTaxClassId('none')
    ->setDescription('description')
    ->setShortDescription('short description')
    ->setOptionsContainer('container1')
    ->setMsrpDisplayActualPriceType(\Magento\Msrp\Model\Product\Attribute\Source\Type::TYPE_ON_GESTURE)
    ->setPrice(20)
    ->setWeight(1)
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setCategoryIds([])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 50, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setSpecialPrice('15.99')
    ->setBrand('40,4040')
    ->save();

$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setName('Simple Product3')
    ->setSku('simple3')
    ->setTaxClassId('none')
    ->setDescription('description')
    ->setShortDescription('short description')
    ->setPrice(30)
    ->setWeight(1)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED)
    ->setWebsiteIds([1])
    ->setCategoryIds([])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 140, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setSpecialPrice('25.99')
    ->setBrand('4040,40')
    ->save();

$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setName('Simple Product4')
    ->setSku('simple4')
    ->setTaxClassId('none')
    ->setDescription('description')
    ->setShortDescription('short description')
    ->setPrice(50)
    ->setWeight(1)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED)
    ->setWebsiteIds([1])
    ->setCategoryIds([])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 140, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setSpecialPrice('55.99')
    ->setBrand('4040,40,4050')
    ->save();
