<?php

namespace MageSuite\BrandManagement\Test\Integration\Model;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class BrandsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    private $brandsRepositoryInterface;

    /**
     * @var \MageSuite\BrandManagement\Model\BrandsFactory
     */
    private $brandsFactory;

    private $store;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->brandsRepositoryInterface = $this->objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
        $this->brandsFactory = $this->objectManager->create(\MageSuite\BrandManagement\Model\BrandsFactory::class);

        $this->store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');

    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testIsNewBrandSavedCorrectlyToDb()
    {
        $brand = $this->brandsRepositoryInterface->getById(600, 1);

        $this->assertEquals('layout update xml', $brand->getLayoutUpdateXml());
        $this->assertEquals(1, $brand->getStoreId());
        $this->assertEquals('url/key', $brand->getUrlKey());
        $this->assertEquals('http://localhost/index.php/brands/url/key', $brand->getBrandUrl());
        $this->assertEquals('test_brand_name', $brand->getBrandName());
        $this->assertEquals(1, $brand->getEnabled());
        $this->assertEquals(1, $brand->getIsFeatured());
        $this->assertEquals('testimage.png', $brand->getBrandIcon());
        $this->assertEquals('Test meta title', $brand->getMetaTitle());
        $this->assertEquals('Test meta description', $brand->getMetaDescription());
        $this->assertEquals('NOINDEX,NOFOLLOW', $brand->getMetaRobots());

        $brand = $this->brandsRepositoryInterface->getById(700, 0);
        $this->assertEquals('layout update xml', $brand->getLayoutUpdateXml());
        $this->assertEquals(0, $brand->getStoreId());
        $this->assertEquals('/mark/test.html', $brand->getUrlKey());
        $this->assertEquals('http://localhost/index.php/mark/test.html', $brand->getBrandUrl());
        $this->assertEquals('test_brand_name', $brand->getBrandName());
        $this->assertEquals(1, $brand->getEnabled());
        $this->assertEquals(1, $brand->getIsFeatured());
        $this->assertEquals('testimage.png', $brand->getBrandIcon());

        $brand = $this->brandsRepositoryInterface->getById(800, 0);

        $this->assertEquals('layout update xml', $brand->getLayoutUpdateXml());
        $this->assertEquals(0, $brand->getStoreId());
        $this->assertEquals('http://example.com', $brand->getUrlKey());
        $this->assertEquals('http://example.com', $brand->getBrandUrl());
        $this->assertEquals('test_brand_name', $brand->getBrandName());
        $this->assertEquals(1, $brand->getEnabled());
        $this->assertEquals(1, $brand->getIsFeatured());
        $this->assertEquals('testimage.png', $brand->getBrandIcon());

        $store = $this->store->load('test333', 'code');

        $brand = $this->brandsRepositoryInterface->getById(1000, $store->getId());

        $this->assertEquals('layout update xml3', $brand->getLayoutUpdateXml());
        $this->assertEquals($store->getId(), $brand->getStoreId());
        $this->assertEquals('urlkey3', $brand->getUrlKey());
        $this->assertEquals('http://localhost/index.php/brands/urlkey3', $brand->getBrandUrl());
        $this->assertEquals('test_brand_name3', $brand->getBrandName());
        $this->assertEquals(1, $brand->getEnabled());
        $this->assertEquals(1, $brand->getIsFeatured());
        $this->assertEquals('testimage3.png', $brand->getBrandIcon());
        $this->assertEquals('Test meta title new store', $brand->getMetaTitle());
        $this->assertEquals('Test meta description new store', $brand->getMetaDescription());
        $this->assertEquals('INDEX,FOLLOW', $brand->getMetaRobots());

        $brand = $this->brandsRepositoryInterface->getById(1100, $store->getId());
        $this->assertEquals('layout update xml3', $brand->getLayoutUpdateXml());
        $this->assertEquals($store->getId(), $brand->getStoreId());
        $this->assertEquals('https://example.com', $brand->getUrlKey());
        $this->assertEquals('https://example.com', $brand->getBrandUrl());
        $this->assertEquals('test_brand_name3', $brand->getBrandName());
        $this->assertEquals(1, $brand->getEnabled());
        $this->assertEquals(1, $brand->getIsFeatured());
        $this->assertEquals('testimage3.png', $brand->getBrandIcon());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testIsEditedBrandSavedCorrectlyToDb()
    {
        $editData = [
            'store_id' => 1,
            'brand_name' => 'edit brand2',
            'layout_update_xml' => 'edit_layout_test2',
            'brand_url_key' => 'url_key2',
            'is_featured' => 0,
            'enabled' => 1,
            'brand_icon' => 'edit_image.jpg',
            'meta_title' => 'Test meta title edit',
            'meta_description' => 'Test meta description edit',
            'meta_robots' => 'INDEX,FOLLOW',
        ];

        $brand = $this->brandsRepositoryInterface->getById(600, $editData['store_id']);
        $brand
            ->setStoreId($editData['store_id'])
            ->setUrlKey($editData['brand_url_key'])
            ->setLayoutUpdateXml($editData['layout_update_xml'])
            ->setBrandName($editData['brand_name'])
            ->setEnabled($editData['enabled'])
            ->setIsFeatured($editData['is_featured'])
            ->setBrandIcon($editData['brand_icon'])
            ->setMetaTitle($editData['meta_title'])
            ->setMetaDescription($editData['meta_description'])
            ->setMetaRobots($editData['meta_robots']);

        $this->brandsRepositoryInterface->save($brand);
        $editedBrand = $this->brandsRepositoryInterface->getById(600, $editData['store_id']);

        $this->assertEquals($editData['layout_update_xml'], $editedBrand->getLayoutUpdateXml());
        $this->assertEquals($editData['store_id'], $editedBrand->getStoreId());
        $this->assertEquals($editData['brand_url_key'], $editedBrand->getUrlKey());
        $this->assertEquals($editData['brand_name'], $editedBrand->getBrandName());
        $this->assertEquals($editData['enabled'], $editedBrand->getEnabled());
        $this->assertEquals($editData['is_featured'], $editedBrand->getIsFeatured());
        $this->assertEquals($editData['brand_icon'], $editedBrand->getBrandIcon());
        $this->assertEquals($editData['meta_title'], $brand->getMetaTitle());
        $this->assertEquals($editData['meta_description'], $brand->getMetaDescription());
        $this->assertEquals($editData['meta_robots'], $brand->getMetaRobots());

        $editData = [
            'store_id' => 0,
            'brand_name' => 'edit brand2',
            'layout_update_xml' => 'edit_layout_test2',
            'brand_url_key' => 'url_key2',
            'is_featured' => 0,
            'enabled' => 1,
            'brand_icon' => 'edit_image.jpg'
        ];

        $brand = $this->brandsRepositoryInterface->getById(700, $editData['store_id']);
        $brand
            ->setStoreId($editData['store_id'])
            ->setUrlKey($editData['brand_url_key'])
            ->setLayoutUpdateXml($editData['layout_update_xml'])
            ->setBrandName($editData['brand_name'])
            ->setEnabled($editData['enabled'])
            ->setIsFeatured($editData['is_featured'])
            ->setBrandIcon($editData['brand_icon']);

        $this->brandsRepositoryInterface->save($brand);
        $editedBrand = $this->brandsRepositoryInterface->getById(700, $editData['store_id']);

        $this->assertEquals($editData['layout_update_xml'], $editedBrand->getLayoutUpdateXml());
        $this->assertEquals($editData['store_id'], $editedBrand->getStoreId());
        $this->assertEquals($editData['brand_url_key'], $editedBrand->getUrlKey());
        $this->assertEquals($editData['brand_name'], $editedBrand->getBrandName());
        $this->assertEquals($editData['enabled'], $editedBrand->getEnabled());
        $this->assertEquals($editData['is_featured'], $editedBrand->getIsFeatured());
        $this->assertEquals($editData['brand_icon'], $editedBrand->getBrandIcon());

        $store = $this->store->load('test333', 'code');

        $editData = [
            'store_id' => $store->getId(),
            'brand_name' => 'edit brand2',
            'layout_update_xml' => 'edit_layout_test2',
            'brand_url_key' => 'url_key2',
            'is_featured' => 0,
            'enabled' => 1,
            'brand_icon' => 'edit_image.jpg'
        ];

        $brand = $this->brandsRepositoryInterface->getById(1000, $editData['store_id']);
        $brand
            ->setStoreId($editData['store_id'])
            ->setUrlKey($editData['brand_url_key'])
            ->setLayoutUpdateXml($editData['layout_update_xml'])
            ->setBrandName($editData['brand_name'])
            ->setEnabled($editData['enabled'])
            ->setIsFeatured($editData['is_featured'])
            ->setBrandIcon($editData['brand_icon']);

        $this->brandsRepositoryInterface->save($brand);
        $editedBrand = $this->brandsRepositoryInterface->getById(1000, $editData['store_id']);

        $this->assertEquals($editData['layout_update_xml'], $editedBrand->getLayoutUpdateXml());
        $this->assertEquals($editData['store_id'], $editedBrand->getStoreId());
        $this->assertEquals($editData['brand_url_key'], $editedBrand->getUrlKey());
        $this->assertEquals($editData['brand_name'], $editedBrand->getBrandName());
        $this->assertEquals($editData['enabled'], $editedBrand->getEnabled());
        $this->assertEquals($editData['is_featured'], $editedBrand->getIsFeatured());
        $this->assertEquals($editData['brand_icon'], $editedBrand->getBrandIcon());

    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testDeleteBrandFromDb(){

        $savedBrand = $this->brandsRepositoryInterface->getById(600);

        $result = $this->brandsRepositoryInterface->delete($savedBrand);

        $this->assertTrue($result);
    }

    public static function loadBrands() {
        include __DIR__.'/../_files/brands.php';
    }

    public static function loadAdditionalStore() {
        include __DIR__.'/../_files/store.php';
    }
}
