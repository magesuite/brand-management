<?php

namespace MageSuite\BrandManagement\Plugin\Framework\UrlInterface;

class CanonicalUrlFix
{
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

        if (!strpos($url, '/index/index/brand/') || !$currentBrand) {
            return $url;
        }

        $brandName = rawurlencode($currentBrand->getBrandName());
        $oldUrl = 'brands/index/index/brand/' . $brandName;
        $newUrl = $this->configuration->getRouteToBrand() . '/' . $currentBrand->getBrandUrlKey();

        return str_replace($oldUrl, $newUrl, $url);
    }
}
