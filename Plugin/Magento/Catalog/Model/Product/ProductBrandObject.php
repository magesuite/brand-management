<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Plugin\Magento\Catalog\Model\Product;

class ProductBrandObject
{
    public function __construct(
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {}

    public function aroundGetData(\Magento\Catalog\Model\Product $subject, callable $proceed, $key = '', $index = null) // phpcs:ignore
    {
        if ($key != 'brand_info') {
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
