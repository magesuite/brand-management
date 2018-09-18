<?php

namespace MageSuite\BrandManagement\Helper;

class Brand extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;


    protected $collectionFactory;

    protected $brandsRepository;

    protected $storeManager;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->eavConfig = $eavConfig;
        $this->brandsRepository = $brandsRepository;
        $this->storeManager = $storeManager;
    }

    public function getBrandsInfo($brandUrlKey = null)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $brand = $this->brandsRepository->getBrandByUrlKey($brandUrlKey, $storeId);

        if(!empty($brand)){
            return $brand;
        }

        return [];
    }

    public function prepareBrandUrlKey($brandUrlKey)
    {
        return strtolower($brandUrlKey);
    }
}
