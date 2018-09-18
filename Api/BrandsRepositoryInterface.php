<?php

namespace MageSuite\BrandManagement\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use MageSuite\BrandManagement\Api\Data\BrandsInterface;

interface BrandsRepositoryInterface
{
    /**
     * @param int $id
     * @param int|null $storeId
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id, $storeId = null);

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     */
    public function save(BrandsInterface $brand);

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface $brand
     * @return void
     */
    public function delete(BrandsInterface $brand);

    /**
     * @param int|string $storeId
     * @return array
     */
    public function getAllBrands($storeId);

    /**
     * @param int|string $brandUrlKey
     * @param int|string $storeId
     * @return object
     */
    public function getBrandByUrlKey($brandUrlKey ,$storeId);

}