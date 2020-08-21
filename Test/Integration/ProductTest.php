<?php

namespace MageSuite\BrandManagement\Test\Integration;

class ProductTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation disabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsCorrectData()
    {
        $productSku = 'samsung_phone';

        $product = $this->productRepository->get($productSku);

        $this->assertEquals('600,700', $product->getBrand());
    }

    public static function loadCategoriesWithProductsFixture()
    {
        include __DIR__.'/_files/store.php';
        include __DIR__.'/_files/brands.php';
        require __DIR__.'/_files/categories_with_products.php';
    }

    public static function loadCategoriesWithProductsFixtureRollback() {
        require __DIR__.'/_files/categories_with_products_rollback.php';
        require __DIR__.'/_files/brands_rollback.php';
        require __DIR__.'/_files/store_rollback.php';
    }
}
