<?php

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands;

class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'MageSuite\BrandManagement\Model\Brands',
            'MageSuite\BrandManagement\Model\ResourceModel\Brands'
        );
    }
}