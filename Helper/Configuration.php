<?php
namespace MageSuite\BrandManagement\Helper;

class Configuration
{
    const BRAND_VISIBILITY_CONFIG_PATH = 'brand_management/brand_visibility/';
    const BRAND_VISIBILITY_PDP = 'pdp';
    const BRAND_VISIBILITY_TILE = 'tile';
    const BRAND_VISIBILITY_CART = 'cart';
    const BRAND_VISIBILITY_MINICART = 'minicart';
    const BRAND_VISIBILITY_ORDER_SUMMARY = 'order_summary';
    const BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE = 'search_autocomplete';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfigValue($path, $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isVisible($location)
    {
        return (bool)$this->getConfigValue(self::BRAND_VISIBILITY_CONFIG_PATH . $location);
    }
}
