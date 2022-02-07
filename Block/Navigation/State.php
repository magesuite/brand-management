<?php

namespace MageSuite\BrandManagement\Block\Navigation;

class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    protected \MageSuite\BrandManagement\Helper\Configuration $configuration;

    protected \Magento\Framework\Registry $registry;

    protected $_template = 'Magento_LayeredNavigation::layer/state.phtml';

    public function __construct(
        \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        array $data = []
    ) {
        $this->configuration = $configuration;
        $this->registry = $registry;
        parent::__construct($context, $layerResolver, $data);
    }

    public function getClearUrl()
    {
        $url = parent::getClearUrl();

        return $this->prepareCleanUrl($url);
    }

    public function prepareCleanUrl($url)
    {
        $routeToBrand = $this->configuration->getRouteToBrand();
        $baseUrl = $this->_urlBuilder->getUrl($routeToBrand . '/*/*');
        $cleanUrl = str_replace($baseUrl, '', $url);
        $cleanUrlParts = explode('/', $cleanUrl);

        if ($cleanUrlParts[0] != \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE
            || !isset($cleanUrlParts[1])) {
            return $url;
        }

        $urlPart = strtolower($cleanUrlParts[1]);
        $brand = $this->registry->registry('current_brand');

        if ($brand) {
            $urlPart = $brand->getBrandUrlKey();
        }

        return $this->_urlBuilder->getUrl($routeToBrand) . $urlPart;
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
