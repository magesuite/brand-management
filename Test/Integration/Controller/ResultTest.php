<?php

namespace MageSuite\BrandManagement\Test\Integration\Controller;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class ResultTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/categories_with_products.php
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
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/categories_with_products.php
     */
    public function testItReturnsCorrectBrandPage()
    {
        $this->dispatch('brands/urlkey');
        $content = $this->getResponse()->getBody();
        $this->assertStringContainsString('Brand: test_brand_name', $content);
        $this->assertStringContainsString("'product_list.filters.brand', 'test_brand_name'", $content);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/categories_with_products.php
     */
    public function testItReturnsCorrectBrandPageWithFilter()
    {
        $this->dispatch('brands/urlkey?cat=334');
        $content = $this->getResponse()->getBody();

        $this->assertStringContainsString('Brand: test_brand_name', $content);
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
