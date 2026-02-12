<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration;

class ProductTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager = null;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation disabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/categories_with_products.php
     */
    public function testItReturnsCorrectData(): void
    {
        $productSku = 'samsung_phone';

        $product = $this->productRepository->get($productSku);

        $this->assertEquals('600,700', $product->getBrand());
    }
}
