<?php
namespace MageSuite\BrandManagement\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_GENERAL_ROUTE_TO_BRAND = 'brand_management/general/route_to_brand';
    const BRAND_VISIBILITY_CONFIG_PATH = 'brand_management/brand_visibility';
    const BRAND_VISIBILITY_PDP = 'pdp';
    const BRAND_VISIBILITY_TILE = 'tile';
    const BRAND_VISIBILITY_CART = 'cart';
    const BRAND_VISIBILITY_MINICART = 'minicart';
    const BRAND_VISIBILITY_ORDER_SUMMARY = 'order_summary';
    const BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE = 'search_autocomplete';
    const BRANDS_OVERVIEW_SEO_CONFIG_PATH = 'brand_management/brands_overview_page_seo';

    protected $config;

    protected $seoConfig;

    public function getRouteToBrand($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_ROUTE_TO_BRAND,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isVisibleOnPdp()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_PDP);
    }

    public function isVisibleOnTile()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_TILE);
    }

    public function isVisibleOnCart()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_CART);
    }

    public function isVisibleOnMiniCart()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_MINICART);
    }

    public function isVisibleOnOrderSummary()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_ORDER_SUMMARY);
    }

    public function isVisibleOnSearchAutocomplete()
    {
        return $this->isVisible(self::BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE);
    }

    public function isVisible($location)
    {
        return (bool)$this->getConfig()->getData($location);
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(self::BRAND_VISIBILITY_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            );
        }
        return $this->config;
    }

    protected function getSeoConfig($storeId = null)
    {
        $store = $storeId ?? 'default';

        if (!isset($this->seoConfig[$store])) {
            $config = $this->scopeConfig->getValue(
                self::BRANDS_OVERVIEW_SEO_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE) ?? [];
            $this->seoConfig[$store] = new \Magento\Framework\DataObject($config);
        }

        return $this->seoConfig[$store];
    }

    public function getSeoMetaTagFor(string $metaTag): ?string
    {
        return $this->getSeoConfig()->getData('meta_' . $metaTag);
    }

}
