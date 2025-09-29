<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form;

class BrandDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public const BRAND_DATA_SCOPE = 'brand';

    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        protected \Magento\Ui\DataProvider\Modifier\PoolInterface $pool,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsCollectionFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $brandsFactory->create();

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    public function getData() // phpcs:ignore
    {
        $data = parent::getData();

        $storeId = (int)$this->request->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        $brandId = (int)$this->request->getParam($this->requestFieldName);

        if (!$brandId) {
            return [];
        }

        $brand = $this->brandsRepository->getById($brandId, $storeId);

        $data[$brandId][self::BRAND_DATA_SCOPE] = $brand->getData();

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    public function getMeta() // phpcs:ignore
    {
        $meta = parent::getMeta();

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
