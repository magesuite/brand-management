<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Source;

class BrandList extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public const CACHE_TAG = 'brand_options_store_%s';

    public function __construct(
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        protected \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Framework\App\CacheInterface $cache
    ) {}

    public function getAllOptions(): array
    {
        $storeId = $this->getAttribute()
            ? $this->getAttribute()->getStoreId()
            : $this->storeManager->getStore()->getId();

        $storeId = (int)$storeId;

        return $this->getBrandsFromStore($storeId);
    }

    protected function getBrandsFromStore(int $storeId): array
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
