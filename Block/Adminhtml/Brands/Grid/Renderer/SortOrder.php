<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

class SortOrder extends \MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer\AbstractColumnRenderer
{
    public function getColumnValue($columnId, $entityId)
    {
        $brandData = $this->getBrandData($entityId);

        if(!$brandData->getEntityId()){
            return '';
        }

        return $brandData->getSortOrder();
    }
}
