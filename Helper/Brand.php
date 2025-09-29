<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Helper;

class Brand
{
    public function __construct(
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
    ) {}

    public function getBrandsInfo(string $brandUrlKey): ?\MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $storeId = (int)$this->storeManager->getStore()->getId();

        try {
            return $this->brandsRepository->getBrandByUrlKey($brandUrlKey, $storeId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException) {
            return null;
        }
    }
}
