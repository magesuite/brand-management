<?php

namespace MageSuite\BrandManagement\Api\Data;

use Magento\Framework\Api\Search\SearchResultInterface;

interface BrandsSearchResultInterface extends SearchResultInterface
{
    /**
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface[]
     */
    public function getItems();

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandsInterface[] $items
     * @return void
     */
    public function setItems(array $items = null);

}
