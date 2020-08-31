<?php
namespace MageSuite\BrandManagement\Model\ItemProvider;

class BrandLinks implements \Magento\Sitemap\Model\ItemProvider\ItemProviderInterface
{
    /**
     * @var \Magento\Sitemap\Model\SitemapItemInterfaceFactory
     */
    protected $itemFactory;

    /**
     * @var \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \MageSuite\BrandManagement\Helper\Sitemap
     */
    protected $configuration;

    public function __construct(
        \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory,
       \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \MageSuite\BrandManagement\Helper\Sitemap $configuration
    ) {
        $this->itemFactory = $itemFactory;
        $this->collectionFactory = $collectionFactory;
        $this->configuration = $configuration;
    }

    public function getItems($storeId)
    {
        if (!$this->configuration->isEnabled($storeId)) {
            return [];
        }

        $priority = $this->configuration->getPriority($storeId);
        $changeFreq = $this->configuration->getChangeFrequency($storeId);
        $items = [
            $this->itemFactory->create([
                'url' => '/' . \MageSuite\BrandManagement\Model\Brand::BRAND_URL,
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ])
        ];

        $brandCollection = $this->collectionFactory->create();
        $brandCollection->setStoreId($storeId);
        $brandCollection->addAttributeToFilter('enabled', 1);
        $brandCollection->addAttributeToSelect('brand_url_key');
        $brandCollection->load();

        foreach ($brandCollection as $brand) {
            $items[] = $this->itemFactory->create([
                'url' => '/' . \MageSuite\BrandManagement\Model\Brand::BRAND_URL . '/' . ltrim($brand->getUrlKey(), '/'),
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ]);
        }
        return $items;
    }
}
