<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Controller\Adminhtml\Brand;

/**
 * @magentoAppArea adminhtml
 */
class SaveTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepositoryInterface = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->brandsRepositoryInterface = $this->_objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
    }

    public function assertSessionMessages(
        \PHPUnit\Framework\Constraint\Constraint $constraint,
        $messageType = null,
        $messageManagerClass = \Magento\Framework\Message\Manager::class
    ): void {
        $this->_assertSessionErrors = false;

        $messages = $this->getMessages($messageType, $messageManagerClass);

        if (empty($messages)) {
            $this->fail('Missing expected error message.');
        }

        $this->assertThat(
            $messages[0],
            $constraint,
            'Session messages do not meet expectations ' . var_export($messages, true)
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveActionWithMissingBrandName(): void
    {
        $editData = [
            'entity_id' => 700,
            'store_id' => 1,
            'brand_name' => 'edit brand2',
        ];
        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->stringStartsWith('Missing required field: brand_name'),
            \Magento\Framework\Message\MessageInterface::TYPE_ERROR
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveActionWithCorrectDataWithoutConfig(): void
    {
        $editData = [
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE => [
                'entity_id' => 600,
                'store_id' => 1,
                'brand_name' => 'edit brand2',
                'layout_update_xml' => 'edit_layout_test2',
                'brand_url_key' => 'url-key2',
                'is_featured' => 0,
                'enabled' => 1,
                'brand_icon' => [
                    0 => [
                        'url' => '',
                        'name' => 'test_image.png',
                    ],
                ],
                'meta_title' => 'Test meta title edit',
                'meta_description' => 'Test meta description edit',
                'meta_robots' => 'INDEX,FOLLOW',
            ],
            \MageSuite\BrandManagement\Controller\Adminhtml\Brand\Save::USE_DEFAULT_SCOPE => [
                'brand_name' => 0,
                'layout_update_xml' => 0,
                'brand_url_key' => 0,
                'is_featured' => 0,
                'enabled' => 0,
                'brand_icon' => 0,
                'meta_title' => 0,
                'meta_description' => 0,
                'meta_robots' => 0,
            ],
        ];
        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved.'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(600, 1);
        $brandData = $editData[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE];

        $this->assertEquals($brandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($brandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($brandData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($brandData['enabled'], $brand->getEnabled());
        $this->assertEquals($brandData['meta_title'], $brand->getMetaTitle());
        $this->assertEquals($brandData['meta_description'], $brand->getMetaDescription());
        $this->assertEquals($brandData['meta_robots'], $brand->getMetaRobots());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveActionWithCorrectDataWithoutConfigDifferentStore(): void
    {
        $editData = [
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE => [
                'entity_id' => 700,
                'store_id' => 0,
                'brand_name' => 'edit brand2',
                'layout_update_xml' => 'edit_layout_test2',
                'brand_url_key' => 'url-key2',
                'is_featured' => 0,
                'enabled' => 1,
                'brand_icon' => [
                    0 => [
                        'url' => '',
                        'name' => 'test_image.png',
                    ],
                ],
            ],
            \MageSuite\BrandManagement\Controller\Adminhtml\Brand\Save::USE_DEFAULT_SCOPE => [
                'brand_name' => 0,
                'layout_update_xml' => 0,
                'brand_url_key' => 0,
                'is_featured' => 0,
                'enabled' => 0,
                'brand_icon' => 0,
            ],
        ];
        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved.'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(700, 0);
        $brandData = $editData[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE];

        $this->assertEquals($brandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($brandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($brandData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($brandData['enabled'], $brand->getEnabled());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveActionWithCorrectDataWithConfig(): void
    {
        $editData = [
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE => [
                'entity_id' => 600,
                'store_id' => 1,
                'brand_name' => 'edit brand2',
                'layout_update_xml' => 'edit_layout_test2',
                'brand_url_key' => 'url_key2',
                'is_featured' => 0,
                'enabled' => 1,
                'brand_icon' => [
                    0 => [
                        'url' => '',
                        'name' => 'test_image.png',
                    ],
                ],
            ],
            \MageSuite\BrandManagement\Controller\Adminhtml\Brand\Save::USE_DEFAULT_SCOPE => [
                'brand_name' => 1,
                'layout_update_xml' => 1,
                'brand_url_key' => 1,
                'is_featured' => 0,
                'enabled' => 0,
                'brand_icon' => 0,
            ],
        ];

        $configBrandData = [
            'brand_name' => 'test_brand_name_600',
            'brand_url_key' => 'url/key',
            'layout_update_xml' => 'layout update xml',
        ];

        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved.'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(600, 1);
        $this->assertEquals($configBrandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($configBrandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($configBrandData['brand_url_key'], $brand->getUrlKey());

        $brandData = $editData[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE];
        $this->assertEquals($brandData['enabled'], $brand->getEnabled());
        $this->assertEquals($brandData['is_featured'], $brand->getIsFeatured());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveActionWithCorrectDataWithConfigDifferentStore(): void
    {
        $editData = [
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE => [
                'entity_id' => 700,
                'store_id' => 1,
                'brand_name' => 'edit brand2',
                'layout_update_xml' => 'edit_layout_test2',
                'brand_url_key' => 'url_key2',
                'is_featured' => 0,
                'enabled' => 1,
                'brand_icon' => [
                    0 => [
                        'url' => '',
                        'name' => 'test_image.png',
                    ],
                ],
                'meta_title' => 'Test meta title edit',
                'meta_description' => 'Test meta description edit',
                'meta_robots' => 'INDEX,FOLLOW',
            ],
            \MageSuite\BrandManagement\Controller\Adminhtml\Brand\Save::USE_DEFAULT_SCOPE => [
                'brand_name' => 1,
                'layout_update_xml' => 1,
                'brand_url_key' => 1,
                'is_featured' => 0,
                'enabled' => 0,
                'brand_icon' => 0,
                'meta_title' => 1,
                'meta_description' => 1,
                'meta_robots' => 1,
            ],
        ];

        $configBrandData = [
            'brand_name' => 'test_brand_name_700',
            'brand_url_key' => '/mark/test.html',
            'layout_update_xml' => 'layout update xml',
            'meta_title' => 'Test meta title 2',
            'meta_description' => 'Test meta description 2',
            'meta_robots' => 'NOINDEX,NOFOLLOW',
        ];

        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved.'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brandData = $editData[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE];
        $brand = $this->brandsRepositoryInterface->getById(700, 1);
        $this->assertEquals($configBrandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($configBrandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($configBrandData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($brandData['enabled'], $brand->getEnabled());
        $this->assertEquals($brandData['is_featured'], $brand->getIsFeatured());
        $this->assertEquals($configBrandData['meta_title'], $brand->getMetaTitle());
        $this->assertEquals($configBrandData['meta_description'], $brand->getMetaDescription());
        $this->assertEquals($configBrandData['meta_robots'], $brand->getMetaRobots());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     */
    public function testSaveBrandWithNonUniqueUrlKey(): void
    {
        $brandData = [
            \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE => [
                'entity_id' => null,
                'brand_name' => 'unique_test_brand_name',
                'brand_url_key' => 'url/key',
            ],
        ];

        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->getRequest()->setPostValue($brandData);
        $this->dispatch('backend/brands/brand/save');

        $this->assertSessionMessages(
            $this->stringStartsWith('Brand with given brand_url_key already exists.'),
            \Magento\Framework\Message\MessageInterface::TYPE_ERROR
        );
    }
}
