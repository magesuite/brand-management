<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Block;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AllTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\BrandManagement\Block\All $block = null;
    protected ?array $brands = null;

    protected ?array $expectedData = [
        [
            'entity_id' => '800',
            'brand_name' => 'é_test_brand_name_with_special_char_as_first_letter',
            'brand_icon' => 'testimage.png',
            'brand_url_key' => 'urlkey3',
            'is_featured' => '1',
            'enabled' => '1',
            'show_in_brand_carousel' => '0',
            'brand_additional_icon' => 'testimage_additional.png',
            'short_description' => 'short description 3',
            'full_description' => 'full description 3',
            'meta_title' => 'Test meta title 3',
            'meta_description' => 'Test meta description 3',
            'meta_robots' => 'INDEX,FOLLOW',
            'hide_headline' => '0',
            'is_searchable' => '1',
        ],
        [
            'entity_id' => '600',
            'brand_name' => 'test_brand_name',
            'brand_icon' => 'testimage.png',
            'brand_url_key' => 'urlkey',
            'is_featured' => '1',
            'enabled' => '1',
            'show_in_brand_carousel' => '0',
            'brand_additional_icon' => 'testimage_additional.png',
            'short_description' => 'short description',
            'full_description' => 'full description',
            'meta_title' => 'Test meta title',
            'meta_description' => 'Test meta description',
            'meta_robots' => 'NOINDEX,NOFOLLOW',
            'hide_headline' => '0',
            'is_searchable' => '1',
        ],
        [
            'entity_id' => '700',
            'brand_name' => 'test_brand_name_2',
            'brand_icon' => 'testimage.png',
            'brand_url_key' => 'urlkey2',
            'is_featured' => '1',
            'enabled' => '1',
            'show_in_brand_carousel' => '0',
            'brand_additional_icon' => 'testimage_additional.png',
            'short_description' => 'short description 2',
            'full_description' => 'full description 2',
            'meta_title' => 'Test meta title 2',
            'meta_description' => 'Test meta description 2',
            'meta_robots' => 'INDEX,FOLLOW',
            'hide_headline' => '0',
            'is_searchable' => '1',
        ],
    ];

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->block = $objectManager->create('MageSuite\BrandManagement\Block\All', ['data' => []]);
        $this->brands = $this->block->getAllBrands();
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     */
    public function testItReturnsBrandsData(): void
    {
        $expectedData = $this->expectedData;
        foreach ($this->brands as $key => $brand) {
            $this->assertInstanceOf(\MageSuite\BrandManagement\Model\Brands::class, $brand);
            $this->assertEquals($expectedData[$key], $brand->getData());

        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_grouped.php
     */
    public function testItReturnsGroupedBrandsData(): void
    {
        $expectedCount = ['a' => 3, 'l' => 1, 'n' => 2, 'é' => 1];
        $expectedOrder = ['a', 'é', 'l', 'n'];
        $brands = $this->block->getBrandsGroupedByFirstLetter();
        $resultOrder = [];
        foreach ($brands as $key => $item) {
            $resultOrder[] = $key;
            $this->assertEquals($expectedCount[$key], count($item));
        }
        $this->assertEquals($expectedOrder, $resultOrder);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     */
    public function testItReturnsCorrectFirstLetter(): void
    {
        $expectedFirstLetters = ['é', 't', 't'];
        foreach ($this->brands as $key => $brand) {
            $this->assertInstanceOf(\MageSuite\BrandManagement\Model\Brands::class, $brand);
            $this->assertEquals(
                $expectedFirstLetters[$key],
                $this->block->getFirstLetter($brand->getBrandName())
            );
        }
    }
}
