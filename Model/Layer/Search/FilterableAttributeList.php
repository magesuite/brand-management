<?php

namespace MageSuite\BrandManagement\Model\Layer\Search;

class FilterableAttributeList extends \Magento\Catalog\Model\Layer\Category\FilterableAttributeList
{
    protected function _prepareAttributeCollection($collection)
    {
        $collection->addSetInfo(true);
        $collection->addIsFilterableFilter();

        return $collection;
    }
}
