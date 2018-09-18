<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

class IsFeatured extends \MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer\AbstractColumnRenderer
{
    /**
     * @param $columnId
     * @param $entityId
     * @return mixed|string
     */
    public function getColumnValue($columnId, $entityId)
    {
        $brandData = $this->getBrandData($entityId);

        if(!$brandData->getEntityId()){
            return '';
        }

        return $brandData->getIsFeatured() ? __('Yes') : __('No');
    }
}