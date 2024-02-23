<?php

namespace MageSuite\BrandManagement\Plugin\SeoLinkMasking\Service\FilterItemUrlProcessor;

class ProcessFilterItemUrlForBrandPage
{
    protected \Magento\Framework\App\RequestInterface $request;

    protected \MageSuite\BrandManagement\Model\BrandsRepository $brandsRepository;

    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected $brands = [];

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\BrandManagement\Model\BrandsRepository $brandsRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->brandsRepository = $brandsRepository;
        $this->storeManager = $storeManager;
    }

    public function afterGetUrl(\MageSuite\SeoLinkMasking\Service\FilterItemUrlProcessor $subject, $result, $category, $requestParameters)
    {
        if ($category || isset($requestParameters['q'])) {
            return $result;
        }

        $currentStore = $this->storeManager->getStore();

        $brand = $this->getBrand($currentStore->getId());

        if (empty($brand)) {
            return $result;
        }

        return $brand->getBrandUrl($currentStore);
    }

    protected function getBrand($currentStoreId)
    {
        $brandName = $this->request->getParam('brand');

        if (empty($brandName)) {
            return null;
        }

        if (isset($this->brands[$brandName])) {
            return $this->brands[$brandName];
        }

        $this->brands[$brandName] = $this->brandsRepository->getBrandByName($brandName, $currentStoreId);

        return $this->brands[$brandName] ?? null;
    }

}
