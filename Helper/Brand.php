<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Helper;

class Brand extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->brandsRepository = $brandsRepository;
    }

    public function getBrandsInfo($brandUrlKey = null)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $brand = $this->brandsRepository->getBrandByUrlKey($brandUrlKey, $storeId);

        if (!empty($brand)) {
            return $brand;
        }

        return [];
    }

    public function prepareBrandUrlKey($brandUrlKey)
    {
        return strtolower($brandUrlKey);
    }
}
