<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

class BrandsRepository implements \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
{
    public function __construct(
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResource,
        protected \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\BrandManagement\Model\Brands\Processor\UploadFactory $uploadFactory,
        protected array $brandAttributes = []
    ) {}

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $brandId, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $brand = $this->brandsFactory->create();
        $brand->setStoreId($storeId);

        $this->brandsResource->load($brand, $brandId);

        if (!$brand->getEntityId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Missing brand with id: %1', $brandId));
        }

        return $brand;
    }

    /**
     * @throws \Exception
     */
    public function save(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): void
    {
        $this->brandsResource->save($brand);
    }

    /**
     * @throws \Exception
     */
    public function delete(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): void
    {
        $this->brandsResource->delete($brand);
    }

    public function getAllBrands(?int $storeId = null): array
    {
        $storeId ??= (int)$this->storeManager->getStore()->getId();

        $brandCollection = $this->collectionFactory->create();
        $brandCollection->setStoreId($storeId);
        $brandCollection->addSortByName();
        $brandCollection->addAttributeToSelect('*');

        return $brandCollection->getItems();
    }

    /**
     * @throws \Exception
     */
    public function create(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $uploader = $this->uploadFactory->create();

        if ($brand->getBrandIconEncodedData()) {
            $uploadResult = $uploader->processUpload($brand->getBrandIconEncodedData());
            $brand->setBrandIcon($uploadResult['image']);
        }

        if ($brand->getBrandAdditionalIconEncodedData()) {
            $uploadResult = $uploader->processUpload($brand->getBrandAdditionalIconEncodedData());
            $brand->setBrandAdditionalIcon($uploadResult['image']);
        }

        $this->save($brand);

        return $brand;
    }

    /**
     * @throws \Exception
     */
    public function update(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $storeId = (int)$this->storeManager->getStore()->getId();

        $brandEntity = $this->getById($brand->getEntityId(), $storeId);
        $brandEntity->addData($brand->getData());

        return $this->create($brandEntity);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function deleteById(int $brandId): void
    {
        $brand = $this->getById($brandId);
        $this->delete($brand);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException|\Magento\Framework\Exception\LocalizedException
     */
    public function getBrandByUrlKey(string $brandUrlKey, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        return $this->getBrandByAttributeValue('brand_url_key', $brandUrlKey, $storeId);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException|\Magento\Framework\Exception\LocalizedException
     */
    public function getBrandByName(string $brandName, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        return $this->getBrandByAttributeValue('brand_name', $brandName, $storeId);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException|\Magento\Framework\Exception\LocalizedException
     */
    protected function getBrandByAttributeValue(string $attributeCode, mixed $attributeValue, ?int $storeId = null): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        if ($storeId == null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $brandCollection = $this->collectionFactory->create();
        $brandCollection->setStoreId($storeId);
        $brandCollection->addAttributeToSelect('*');
        $brandCollection->addAttributeToFilter($attributeCode, ['eq' => $attributeValue]);

        if (empty($brandCollection->getItems())) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Brand not found'));
        }

        return $brandCollection->getFirstItem();
    }
}
