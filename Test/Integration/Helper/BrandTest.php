<?php

namespace MageSuite\BrandManagement\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class BrandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\BrandManagement\Helper\Brand
     */
    private $brandHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->brandHelper = $this->objectManager->create(\MageSuite\BrandManagement\Helper\Brand::class);
    }

    public static function loadCategoriesWithProductsFixture()
    {
        require __DIR__.'/../_files/categories_with_products.php';

        $indexerRegistry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Framework\Indexer\IndexerRegistry::class);
        $indexerRegistry->get(\Magento\CatalogSearch\Model\Indexer\Fulltext::INDEXER_ID)->reindexAll();
    }

    public static function loadCategoriesWithProductsFixtureRollback()
    {
        require __DIR__.'/../_files/categories_with_products_rollback.php';
    }

    public static function loadBrands() {
        include __DIR__.'/../_files/brands_integration.php';
    }
    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadBrands
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsBrandsData()
    {
        $brand = $this->brandHelper->getBrandsInfo('urlkey');

        $this->assertEquals('layoutupdatexml', $brand->getLayoutUpdateXml());
        $this->assertEquals('testimage.png', $brand->getBrandIcon());
        $this->assertEquals('test_brand_name', $brand->getBrandName());
        $this->assertEquals('urlkey', $brand->getBrandUrlKey());
        $this->assertEquals('testimage_additional.png', $brand->getBrandAdditionalIcon());
        $this->assertEquals('Test meta title', $brand->getMetaTitle());
        $this->assertEquals('Test meta description', $brand->getMetaDescription());
        $this->assertEquals('NOINDEX,NOFOLLOW', $brand->getMetaRobots());
    }
}
