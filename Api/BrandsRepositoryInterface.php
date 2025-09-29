<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Api;

interface BrandsRepositoryInterface
{
    /**
     * @param int $brandId
     * @param int $storeId
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $brandId, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     *
     * @return void
     *
     * @throws \Exception
     */
    public function save(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): void;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     *
     * @return void
     */
    public function delete(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): void;

    /**
     * @param int|null $storeId
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface[]
     */
    public function getAllBrands(?int $storeId = null): array;

    /**
     * @param string $brandUrlKey
     * @param int $storeId
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBrandByUrlKey(string $brandUrlKey, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface;

    /**
     * @param string $brandName
     * @param int $storeId
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBrandByName(string $brandName, int $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID): \MageSuite\BrandManagement\Api\Data\BrandsInterface;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     *
     * @throws \Exception
     */
    public function create(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): \MageSuite\BrandManagement\Api\Data\BrandsInterface;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     *
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     *
     * @throws \Exception
     */
    public function update(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): \MageSuite\BrandManagement\Api\Data\BrandsInterface;

    /**
     * @param int $brandId
     *
     * @return void
     */
    public function deleteById(int $brandId): void;

}
