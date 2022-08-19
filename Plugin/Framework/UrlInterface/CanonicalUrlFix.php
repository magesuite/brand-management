<?php

namespace MageSuite\BrandManagement\Plugin\Framework\UrlInterface;

class CanonicalUrlFix
{
    const ROUTE_BRAND_OVERVIEW_PAGE = 'brands/index/all';

    protected \MageSuite\BrandManagement\Helper\Configuration $configuration;

    protected \Magento\Framework\Registry $registry;

    public function __construct(
        \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \Magento\Framework\Registry $registry
    ) {
        $this->configuration = $configuration;
        $this->registry = $registry;
    }

    public function afterGetUrl(\Magento\Framework\UrlInterface $subject, $result)
    {
        return $this->fixUrl($result);
    }

    protected function fixUrl($url)
    {
        /** @var \MageSuite\BrandManagement\Model\Brands $currentBrand */
        $currentBrand = $this->registry->registry('current_brand');

        if (strpos($url, self::ROUTE_BRAND_OVERVIEW_PAGE) !== false) {
            $url = str_replace(self::ROUTE_BRAND_OVERVIEW_PAGE, $this->configuration->getRouteToBrand(), $url);
        }

        if (strpos($url, '/index/index/brand/') !== false && $currentBrand) {
            $brandName = rawurlencode($currentBrand->getBrandName());
            $oldUrl = 'brands/index/index/brand/' . $brandName;
            $newUrl = sprintf('%s/%s', $this->configuration->getRouteToBrand(), $currentBrand->getBrandUrlKey());
            $url = str_replace($oldUrl, $newUrl, $url);
        }

        return $url;
    }
}
