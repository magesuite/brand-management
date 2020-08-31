<?php

namespace MageSuite\BrandManagement\Helper;

class Sitemap extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_CONFIG_PATH_ENABLED = 'brand_management/sitemap/enabled';
    const XML_CONFIG_PATH_PRIORITY = 'brand_management/sitemap/priority';
    const XML_CONFIG_PATH_CHANGE_FREQUENCY = 'brand_management/sitemap/changefreq';

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getPriority($storeId = null)
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_PRIORITY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getChangeFrequency($storeId)
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_CHANGE_FREQUENCY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
