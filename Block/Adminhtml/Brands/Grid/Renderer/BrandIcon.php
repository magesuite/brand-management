<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

class BrandIcon extends \MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer\AbstractColumnRenderer
{
    /**
     * @param $columnId
     * @param $entityId
     * @return mixed|string
     */
    public function getColumnValue($columnId, $entityId)
    {
        $brandData = $this->getBrandData($entityId);

        if (!$brandData->getBrandIcon()) {
            return '';
        }

        $iconPath = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath() . 'brands/' . $brandData->getBrandIcon();

        if (!file_exists($iconPath)) {
            return '';
        }
        return sprintf('<img src="%s" width="250"/>', $brandData->getBrandIconUrl());
    }
}
