<?php

namespace MageSuite\BrandManagement\Test\Integration\Controller\Adminhtml\Brand;

/**
 * @magentoAppArea adminhtml
 */
class SaveTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /** @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface */
    protected $brandsRepositoryInterface;

    protected function setUp(): void
    {
        parent::setUp();

        $this->brandsRepositoryInterface = $this->_objectManager->create(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);
    }

    /**
     * Utilize backend session model by default
     *
     * @param \PHPUnit\Framework\Constraint\Constraint $constraint
     * @param string|null $messageType
     * @param string $messageManagerClass
     */
    public function assertSessionMessages(
        \PHPUnit\Framework\Constraint\Constraint $constraint,
        $messageType = null,
        $messageManagerClass = \Magento\Framework\Message\Manager::class
    ) {
        $this->_assertSessionErrors = false;

        $messages = $this->getMessages($messageType, $messageManagerClass);
        $this->assertThat(
            $messages[0],
            $constraint,
            'Session messages do not meet expectations ' . var_export($messages, true)
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testSaveActionWithWrongData()
    {
        $editData = [
            'entity_id' => 700,
            'store_id' => 1,
            'brand_name' => 'edit brand2'
        ];
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->stringStartsWith('Notice: Undefined index'),
            \Magento\Framework\Message\MessageInterface::TYPE_ERROR
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testSaveActionWithCorrectDataWithoutConfig()
    {
        $editData = [
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
                    'name' => 'test_image.png'
                ]
            ],
            'meta_title' => 'Test meta title edit',
            'meta_description' => 'Test meta description edit',
            'meta_robots' => 'INDEX,FOLLOW',
            'use_config' => [
                'brand_name' => 'false',
                'layout_update_xml' => 'false',
                'brand_url_key' => 'false',
                'is_featured' => 'false',
                'enabled' => 'false',
                'brand_icon' => 'false',
                'meta_title' => 'false',
                'meta_description' => 'false',
                'meta_robots' => 'false'
            ]
        ];
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(600, 1);
        $this->assertEquals($editData['brand_name'], $brand->getBrandName());
        $this->assertEquals($editData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($editData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($editData['enabled'], $brand->getEnabled());
        $this->assertEquals($editData['meta_title'], $brand->getMetaTitle());
        $this->assertEquals($editData['meta_description'], $brand->getMetaDescription());
        $this->assertEquals($editData['meta_robots'], $brand->getMetaRobots());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testSaveActionWithCorrectDataWithoutConfigDifferentStore()
    {
        $editData = [
            'entity_id' => 700,
            'store_id' => 0,
            'brand_name' => 'edit brand2',
            'layout_update_xml' => 'edit_layout_test2',
            'brand_url_key' => 'url_key2',
            'is_featured' => 0,
            'enabled' => 1,
            'brand_icon' => [
                0 => [
                    'url' => '',
                    'name' => 'test_image.png'
                ]
            ],
            'use_config' => [
                'brand_name' => 'false',
                'layout_update_xml' => 'false',
                'brand_url_key' => 'false',
                'is_featured' => 'false',
                'enabled' => 'false',
                'brand_icon' => 'false'
            ]
        ];
        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(700, 0);
        $this->assertEquals($editData['brand_name'], $brand->getBrandName());
        $this->assertEquals($editData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($editData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($editData['enabled'], $brand->getEnabled());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testSaveActionWithCorrectDataWithConfig()
    {
        $editData = [
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
                    'name' => 'test_image.png'
                ]
            ],
            'use_config' => [
                'brand_name' => 'true',
                'layout_update_xml' => 'true',
                'brand_url_key' => 'true',
                'is_featured' => 'false',
                'enabled' => 'false',
                'brand_icon' => 'false'
            ]
        ];

        $configBrandData = [
            'brand_name' => 'test_brand_name',
            'brand_url_key' => 'url/key',
            'layout_update_xml' => 'layout update xml'
        ];

        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(600, 1);
        $this->assertEquals($configBrandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($configBrandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($configBrandData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($editData['enabled'], $brand->getEnabled());
        $this->assertEquals($editData['is_featured'], $brand->getIsFeatured());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     */
    public function testSaveActionWithCorrectDataWithConfigDifferentStore()
    {
        $editData = [
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
                    'name' => 'test_image.png'
                ]
            ],
            'meta_title' => 'Test meta title edit',
            'meta_description' => 'Test meta description edit',
            'meta_robots' => 'INDEX,FOLLOW',
            'use_config' => [
                'brand_name' => 'true',
                'layout_update_xml' => 'true',
                'brand_url_key' => 'true',
                'is_featured' => 'false',
                'enabled' => 'false',
                'brand_icon' => 'false',
                'meta_title' => 'true',
                'meta_description' => 'true',
                'meta_robots' => 'true',
            ]
        ];

        $configBrandData = [
            'brand_name' => 'test_brand_name',
            'brand_url_key' => '/mark/test.html',
            'layout_update_xml' => 'layout update xml',
            'meta_title' => 'Test meta title 2',
            'meta_description' => 'Test meta description 2',
            'meta_robots' => 'NOINDEX,NOFOLLOW',
        ];

        $this->getRequest()->setPostValue($editData);
        $this->dispatch('backend/brands/brand/save');
        $this->assertSessionMessages(
            $this->equalTo('Brand has been saved'),
            \Magento\Framework\Message\MessageInterface::TYPE_SUCCESS
        );
        $this->assertRedirect($this->stringContains('/backend/brands/brand/edit'));

        $brand = $this->brandsRepositoryInterface->getById(700, 1);
        $this->assertEquals($configBrandData['brand_name'], $brand->getBrandName());
        $this->assertEquals($configBrandData['layout_update_xml'], $brand->getLayoutUpdateXml());
        $this->assertEquals($configBrandData['brand_url_key'], $brand->getUrlKey());
        $this->assertEquals($editData['enabled'], $brand->getEnabled());
        $this->assertEquals($editData['is_featured'], $brand->getIsFeatured());
        $this->assertEquals($configBrandData['meta_title'], $brand->getMetaTitle());
        $this->assertEquals($configBrandData['meta_description'], $brand->getMetaDescription());
        $this->assertEquals($configBrandData['meta_robots'], $brand->getMetaRobots());
    }

    public static function loadBrands() {
        include __DIR__.'/../../../_files/brands.php';
    }

    public static function loadAdditionalStore() {
        include __DIR__.'/../../../_files/store.php';
    }
}

