<?php
namespace MageSuite\BrandManagement\Test\Integration\Model\ItemProvider;

class BrandLinksTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \MageSuite\BrandManagement\Model\ItemProvider\BrandLinks
     */
    protected $brandLinksProvider;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->storeRepository = $this->objectManager->get(\Magento\Store\Api\StoreRepositoryInterface::class);
        $this->brandLinksProvider = $this->objectManager->get(\MageSuite\BrandManagement\Model\ItemProvider\BrandLinks::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadAdditionalStore
     * @magentoDataFixture loadBrands
     * @magentoConfigFixture default_store sitemap/category/priority 1
     * @magentoConfigFixture default_store sitemap/category/changefreq daily
     */
    public function testItReturnCorrectLinks()
    {
        $defaultStoreExpectedItems = [
            0 => [
                'url' => '/brands',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ],
            1 => [
                'url' => '/brands/url/key',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ],
            2 => [
                'url' => '/brands//mark/test.html',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ],
            3 => [
                'url' => '/brands/http://example.com',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ]
        ];

        $defaultStoreItems = $this->brandLinksProvider->getItems(1);
        foreach ($defaultStoreItems as $index => $item) {
            $this->assertEquals($defaultStoreExpectedItems[$index]['url'], $item->getUrl());
            $this->assertEquals($defaultStoreExpectedItems[$index]['priority'], $item->getPriority());
            $this->assertEquals($defaultStoreExpectedItems[$index]['changeFrequency'], $item->getChangeFrequency());
        }
    }

    public static function loadBrands()
    {
        include __DIR__ . '/../../_files/brands.php';
    }

    public static function loadAdditionalStore()
    {
        include __DIR__ . '/../../_files/store.php';
    }
}
