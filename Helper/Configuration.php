<?php
namespace MageSuite\BrandManagement\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BRAND_VISIBILITY_CONFIG_PATH = 'brand_management/brand_visibility';
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

    protected $config = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
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
}
