<?php

namespace MageSuite\BrandManagement\Block\Navigation;

class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    protected $_template = 'Magento_LayeredNavigation::layer/state.phtml';

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        array $data = []
    )
    {
        $this->registry = $registry;
        parent::__construct($context, $layerResolver, $data);
    }

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

        if($currBrand = $this->registry->registry('current_brand')){
            $urlPart = $currBrand->getBrandUrlKey();
        } else {
            $urlPart = strtolower($cleanUrlParts[1]);
        }

        return $this->_urlBuilder->getUrl(\MageSuite\BrandManagement\Model\Brand::BRAND_URL) . $urlPart;
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
