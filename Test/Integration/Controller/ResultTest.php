<?php

namespace MageSuite\BrandManagement\Test\Integration\Controller;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class ResultTest extends \Magento\TestFramework\TestCase\AbstractController
{
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
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadBrands
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsIndexPage()
    {
        $this->dispatch('brands/none');

        $this->assertEquals('index', $this->getRequest()->getControllerName());
        $this->assertEquals('', $this->getResponse()->getBody());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadBrands
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsCorrectBrandPage()
    {
        $this->markTestSkipped('Test skipped to fix it later');
        $this->dispatch('brands/urlkey');
        $content = $this->getResponse()->getBody();
        $this->assertContains('Brand: test_brand_name', $content);
        $this->assertContains("'product_list.filters.brand', 'test_brand_name'", $content);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadBrands
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsCorrectBrandPageWithFilter()
    {
        $this->markTestSkipped('Test skipped to fix it later');
        $this->dispatch('brands/urlkey?cat=334');
        $content = $this->getResponse()->getBody();

        $this->assertContains('Brand: test_brand_name', $content);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brand_with_products.php
     * @magentoConfigFixture current_store catalog/frontend/grid_per_page 2
     */
    public function testItReturnsCorrectUrlInPager()
    {
        $this->dispatch('brands/brand_url_key?color=red&p=2');

        $content = $this->getResponse()->getBody();
        $this->assertStringContainsString('Test brand', $content);

        $this->assertStringNotContainsString('http://localhost/index.php/brand/Test%20brand/color/Red/p/2/?p=2', $content);
        $this->assertStringContainsString('http://localhost/index.php/brand/Test%20brand/?color=red&p=2', $content);
    }
}
