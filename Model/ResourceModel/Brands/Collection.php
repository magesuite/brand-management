<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands;

class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(
            \MageSuite\BrandManagement\Model\Brands::class,
            \MageSuite\BrandManagement\Model\ResourceModel\Brands::class
        );
    }

    public function addSortByName(): self
    {
        $this->addAttributeToSort('brand_name');
        return $this;
    }

    public function toOptionArray(): array
    {
        return $this->_toOptionArray('entity_id', 'brand_name');
    }
}
