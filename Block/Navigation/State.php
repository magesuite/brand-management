<?php

namespace MageSuite\BrandManagement\Block\Navigation;

class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    protected $_template = 'Magento_LayeredNavigation::layer/state.phtml';

    public function getClearUrl()
    {
        $url = parent::getClearUrl();

        $url = $this->prepareCleanUrl($url);

        return $url;
    }

    public function prepareCleanUrl($url)
    {
        $baseUrl = $this->_urlBuilder->getUrl(\MageSuite\BrandManagement\Model\Brand::BRAND_URL . '/*/*');
        $cleanUrl = str_replace($baseUrl, '', $url);

        $cleanUrlParts = explode('/', $cleanUrl);

        if(
            $cleanUrlParts[0] != \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE ||
            !isset($cleanUrlParts[1])
        ){
            return $url;
        }

        return $this->_urlBuilder->getUrl(\MageSuite\BrandManagement\Model\Brand::BRAND_URL) . strtolower($cleanUrlParts[1]);
    }

    public function getActiveFilters()
    {
        $filters = $this->getLayer()->getState()->getFilters();

        $displayedFilters = array_filter(
            $filters,
            function ($filter) {
                return $filter->getFilter()->getRequestVar() != \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE;
            }
        );

        if (!is_array($displayedFilters)) {
            $displayedFilters = [];
        }

        return $displayedFilters;
    }
}
