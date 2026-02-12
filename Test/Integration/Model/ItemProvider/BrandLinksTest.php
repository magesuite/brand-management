<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Model\ItemProvider;

class BrandLinksTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager = null;
    protected ?\Magento\Store\Api\StoreRepositoryInterface $storeRepository = null;
    protected ?\MageSuite\BrandManagement\Model\ItemProvider\BrandLinks $brandLinksProvider = null;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->storeRepository = $this->objectManager->get(\Magento\Store\Api\StoreRepositoryInterface::class);
        $this->brandLinksProvider = $this->objectManager->get(\MageSuite\BrandManagement\Model\ItemProvider\BrandLinks::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/store.php
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands.php
     * @magentoConfigFixture default_store brand_management/sitemap/enabled 1
     * @magentoConfigFixture default_store brand_management/sitemap/priority 1
     * @magentoConfigFixture default_store brand_management/sitemap/changefreq daily
     */
    public function testItReturnCorrectLinks(): void
    {
        $defaultStoreExpectedItems = [
            0 => [
                'url' => '/brands',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ],
            1 => [
                'url' => '/brands/http://example.com',
                'priority' => '1',
                'changeFrequency' => 'daily',
            ],
            2 => [
                'url' => '/brands/url/key',
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
}
