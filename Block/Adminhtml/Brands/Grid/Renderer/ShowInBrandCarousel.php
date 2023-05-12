<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

class ShowInBrandCarousel extends \MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer\AbstractColumnRenderer
{
    /**
     * @param $columnId
     * @param $entityId
     * @return mixed|string
     */
    public function getColumnValue($columnId, $entityId)
    {
        $brandData = $this->getBrandData($entityId);

        if(!$brandData->getShowInBrandCarousel()){
            return '';
        }

        return $brandData->getShowInBrandCarousel() ? __('Yes') : __('No');
    }
}
