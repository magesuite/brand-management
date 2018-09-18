<?php
namespace MageSuite\BrandManagement\Plugin;

class ProductBrandObject
{
    /**
     * @var \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory
     */
    private $brandsFactory;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    private $storeManager;

    public function __construct(
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->brandsFactory = $brandsFactory;
        $this->storeManager = $storeManager;
    }

    public function aroundGetData($subject, callable $proceed, $key = '', $index = null)
    {
        if($key != 'brand_info'){
            return $proceed($key, $index);
        }

        $returnValue = $proceed('brand', $index);

        if ($returnValue) {
            $brandIds = explode(',', $returnValue);

            $brandCollection = $this->brandsFactory->create();
            $brandCollection
                ->setStoreId($this->storeManager->getStore()->getId())
                ->addFieldToFilter('entity_id', $brandIds)
                ->addAttributeToSelect('*');

            $returnValue = $brandCollection->getSize() ? $brandCollection->getItems() : null;
        }

        return $returnValue;
    }
}
