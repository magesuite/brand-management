<?php

namespace MageSuite\BrandManagement\Model\Source;

class BrandList extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const CACHE_TAG = 'brand_options_store_%s';

    protected $collectionFactory;

    protected $model;

    protected $brandsRepository;

    protected $storeManager;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    public function __construct(
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \MageSuite\BrandManagement\Model\BrandsFactory $model,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->brandsRepository = $brandsRepository;
        $this->storeManager = $storeManager;
        $this->model = $model;
        $this->cache = $cache;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function getAllOptions()
    {
        $storeId = $this->getAttribute()->getStoreId();

        if ($storeId == null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        return $this->getBrandsFromStore($storeId);
    }

    private function getBrandsFromStore($storeId)
    {
        $cacheKey = sprintf(self::CACHE_TAG, $storeId);

        $options = json_decode($this->cache->load($cacheKey), true);

        if (is_array($options) and !empty($options)) {
            return $options;
        }

        $brandsCollection = $this->collectionFactory->create();
        $brandsCollection->setStoreId($storeId);

        $options = [];

        foreach ($brandsCollection as $brand) {
            $brand = $this->brandsRepository->getById($brand->getEntityId(), $storeId);

            $options[] = [
                'label' => $brand->getBrandName(),
                'value' => $brand->getEntityId()
            ];
        }

        $this->cache->save(json_encode($options), $cacheKey, [\MageSuite\BrandManagement\Model\Brands::CACHE_TAG]);

        return $options;
    }
}
