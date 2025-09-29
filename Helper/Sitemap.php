<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Helper;

class Sitemap extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const XML_CONFIG_PATH_ENABLED = 'brand_management/sitemap/enabled';
    public const XML_CONFIG_PATH_PRIORITY = 'brand_management/sitemap/priority';
    public const XML_CONFIG_PATH_CHANGE_FREQUENCY = 'brand_management/sitemap/changefreq';

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getPriority(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_PRIORITY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getChangeFrequency(?int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_CHANGE_FREQUENCY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
