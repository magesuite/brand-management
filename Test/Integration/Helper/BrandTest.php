<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class BrandTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager = null;
    protected ?\MageSuite\BrandManagement\Helper\Brand $brandHelper = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->brandHelper = $this->objectManager->create(\MageSuite\BrandManagement\Helper\Brand::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/categories_with_products.php
     */
    public function testItReturnsBrandsData(): void
    {
        $brand = $this->brandHelper->getBrandsInfo('urlkey');

        $this->assertEquals('testimage.png', $brand->getBrandIcon());
        $this->assertEquals('test_brand_name', $brand->getBrandName());
        $this->assertEquals('urlkey', $brand->getBrandUrlKey());
        $this->assertEquals('testimage_additional.png', $brand->getBrandAdditionalIcon());
        $this->assertEquals('Test meta title', $brand->getMetaTitle());
        $this->assertEquals('Test meta description', $brand->getMetaDescription());
        $this->assertEquals('NOINDEX,NOFOLLOW', $brand->getMetaRobots());
    }
}
