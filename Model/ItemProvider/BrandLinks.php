<?php
namespace MageSuite\BrandManagement\Model\ItemProvider;

class BrandLinks implements \Magento\Sitemap\Model\ItemProvider\ItemProviderInterface
{
    /**
     * @var \Magento\Sitemap\Model\SitemapItemInterfaceFactory
     */
    protected $itemFactory;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandRepository;

    /**
     * @var \Magento\Sitemap\Model\ItemProvider\CategoryConfigReader
     */
    protected $categoryConfigReader;

    public function __construct(
        \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandRepository,
        \Magento\Sitemap\Model\ItemProvider\CategoryConfigReader $categoryConfigReader
    ) {
        $this->itemFactory = $itemFactory;
        $this->brandRepository = $brandRepository;
        $this->categoryConfigReader = $categoryConfigReader;
    }

    public function getItems($storeId)
    {
        $priority = $this->categoryConfigReader->getPriority($storeId);
        $changeFreq = $this->categoryConfigReader->getChangeFrequency($storeId);
        $items = [
            $this->itemFactory->create([
                'url' => '/' . \MageSuite\BrandManagement\Model\Brand::BRAND_URL,
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ])
        ];
        $brands = array_filter($this->brandRepository->getAllBrands($storeId), function ($brand) {
            return boolval($brand->getEnabled()) === true && !empty($brand->getUrlKey());
        });
        foreach ($brands as $brand) {
            $items[] = $this->itemFactory->create([
                'url' => '/' . \MageSuite\BrandManagement\Model\Brand::BRAND_URL . '/' . $brand->getUrlKey(),
                'priority' => $priority,
                'changeFrequency' => $changeFreq
            ]);
        }
        return $items;
    }
}
