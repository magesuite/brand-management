<?php

declare(strict_types=1);

$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();

/** @var \Magento\Framework\Registry $registry */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$brandRepository = $objectManager->create('MageSuite\BrandManagement\Api\BrandsRepositoryInterface');

foreach ([600,700,40,4040,4050] as $brandId) {
    $brand = $objectManager->create('MageSuite\BrandManagement\Model\Brands');

    $brand->load($brandId);

    if ($brand->getId() > 0) {
        $brand->delete();
    }
}

$resolver->requireDataFixture('MageSuite_BrandManagement::Test/Integration/_files/store_rollback.php');
