<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ItemProvider;

class BrandLinks implements \Magento\Sitemap\Model\ItemProvider\ItemProviderInterface
{
    protected \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory;

    protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory;

    protected \MageSuite\BrandManagement\Helper\Configuration $configuration;

    protected \MageSuite\BrandManagement\Helper\Sitemap $sitemapConfiguration;

    public function __construct(
        \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \MageSuite\BrandManagement\Helper\Sitemap $sitemapConfiguration
    ) {
        $this->itemFactory = $itemFactory;
        $this->collectionFactory = $collectionFactory;
        $this->configuration = $configuration;
        $this->sitemapConfiguration = $sitemapConfiguration;
    }

    public function getItems($storeId): array
    {
        if (!$this->sitemapConfiguration->isEnabled($storeId)) {
            return [];
        }

        $routeToBrand = $this->configuration->getRouteToBrand($storeId);
        $priority = $this->sitemapConfiguration->getPriority($storeId);
        $changeFreq = $this->sitemapConfiguration->getChangeFrequency($storeId);
        $items = [
            $this->itemFactory->create([
                'url' => '/' . $routeToBrand,
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ])
        ];

        foreach ($this->getCollection() as $brand) {
            $url = sprintf(
                '/%s/%s',
                $routeToBrand,
                ltrim($brand->getUrlKey(), '/')
            );
            $items[] = $this->itemFactory->create([
                'url' => $url,
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ]);
        }

        return $items;
    }

    public function getCollection($storeId = null): \MageSuite\BrandManagement\Model\ResourceModel\Brands\Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToFilter('enabled', 1);
        $collection->addAttributeToSelect('brand_url_key');
        $collection->addAttributeToFilter('brand_url_key', ['nlike' => '/%']);

        return $collection;
    }
}
