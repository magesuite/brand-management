<?php

namespace MageSuite\BrandManagement\Plugin;

class CanonicalUrlFix
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function afterGetUrl(\Magento\Framework\View\Element\AbstractBlock $subject, $result)
    {
        return $this->fixUrl($result);
    }

    public function afterGetCanonicalUrl(\Magento\Framework\View\Element\AbstractBlock $subject, $result)
    {
        return $this->fixUrl($result);
    }

    protected function fixUrl($url)
    {
        /** @var \MageSuite\BrandManagement\Model\Brands $currentBrand */
        $currentBrand = $this->registry->registry('current_brand');
        if (!$currentBrand) {
            return $url;
        }

        if (!strpos($url, '/index/index/brand/')) {
            return $url;
        }

        $brandName = rawurlencode($currentBrand->getBrandName());

        $url = str_replace('/index/index/brand/' . $brandName, '/' . $currentBrand->getBrandUrlKey(), $url);

        return $url;
    }
}
