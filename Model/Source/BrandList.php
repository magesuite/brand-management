<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Source;

class BrandList extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const CACHE_TAG = 'brand_options_store_%s';

    protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory;

    protected \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory;

    protected \Magento\Framework\Serialize\SerializerInterface $serializer;

    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected \Magento\Framework\App\CacheInterface $cache;

    public function __construct(
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->brandsFactory = $brandsFactory;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->cache = $cache;
    }

    public function getAllOptions(): array
    {
        $storeId = $this->getAttribute()
            ? $this->getAttribute()->getStoreId()
            : null;

        if ($storeId == null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        return $this->getBrandsFromStore($storeId);
    }

    protected function getBrandsFromStore($storeId): array
    {
        $cacheKey = sprintf(self::CACHE_TAG, $storeId);
        $options = $this->cache->load($cacheKey);

        if (!empty($options)) {
            $brands = $this->serializer->unserialize($options);

            if (!empty($brands)) {
                return $brands;
            }
        }

        $brandsCollection = $this->collectionFactory->create();
        $brandsCollection->setStoreId($storeId);
        $brandsCollection->addSortByName();
        $options = $brandsCollection->toOptionArray();

        if (!empty($options)) {
            $this->cache->save(
                $this->serializer->serialize($options),
                $cacheKey,
                [\MageSuite\BrandManagement\Model\Brands::CACHE_TAG]
            );
        }

        return $options;
    }
}
