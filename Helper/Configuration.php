<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const XML_PATH_GENERAL_ROUTE_TO_BRAND = 'brand_management/general/route_to_brand';
    public const XML_PATH_GENERAL_SHOW_SAFETY_REGULATIONS = 'brand_management/general/show_safety_regulations';
    public const XML_PATH_GENERAL_WYSIWYG_SAFETY_REGULATIONS = 'brand_management/general/wysiwyg_safety_regulations';
    public const BRAND_VISIBILITY_CONFIG_PATH = 'brand_management/brand_visibility';
    public const BRAND_VISIBILITY_PDP = 'pdp';
    public const BRAND_VISIBILITY_TILE = 'tile';
    public const BRAND_VISIBILITY_CART = 'cart';
    public const BRAND_VISIBILITY_MINICART = 'minicart';
    public const BRAND_VISIBILITY_ORDER_SUMMARY = 'order_summary';
    public const BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE = 'search_autocomplete';
    public const BRAND_VISIBILITY_SEARCH_MAX_RESULT = 'search_max_result';
    public const BRANDS_OVERVIEW_SEO_CONFIG_PATH = 'brand_management/brands_overview_page_seo';

    /**
     * @var \Magento\Framework\DataObject[] $seoConfig
     */
    protected ?array $seoConfig = null;
    protected ?\Magento\Framework\DataObject $config = null;

    public function isWysiwygForSafetyRegulationsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_GENERAL_WYSIWYG_SAFETY_REGULATIONS);
    }

    public function isShowSafetyRegulationsEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GENERAL_SHOW_SAFETY_REGULATIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getRouteToBrand(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_ROUTE_TO_BRAND,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isVisibleOnPdp(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_PDP);
    }

    public function isVisibleOnTile(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_TILE);
    }

    public function isVisibleOnCart(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_CART);
    }

    public function isVisibleOnMiniCart(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_MINICART);
    }

    public function isVisibleOnOrderSummary(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_ORDER_SUMMARY);
    }

    public function isVisibleOnSearchAutocomplete(): bool
    {
        return $this->isVisible(self::BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE);
    }

    public function getSearchMaxResult(): int
    {
        return (int) $this->getConfig()->getData(self::BRAND_VISIBILITY_SEARCH_MAX_RESULT);
    }

    public function isVisible(string $location): bool
    {
        return (bool) $this->getConfig()->getData($location);
    }

    protected function getConfig(): \Magento\Framework\DataObject
    {
        if ($this->config === null) {
            $this->config = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(
                    self::BRAND_VISIBILITY_CONFIG_PATH,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );
        }

        return $this->config;
    }

    protected function getSeoConfig(?int $storeId = null): \Magento\Framework\DataObject
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
