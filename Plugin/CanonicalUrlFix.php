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
        /** @var \MageSuite\BrandManagement\Model\Brands $currentBrand */
        $currentBrand = $this->registry->registry('current_brand');

        if (!$currentBrand) {
            return $result;
        }

        if(!strpos($result, '/index/index/brand/')) {
            return $result;
        }

        $brandName = rawurlencode($currentBrand->getBrandName());

        $result = str_replace('/index/index/brand/' . $brandName, '/' . $currentBrand->getBrandUrlKey(), $result);

        return $result;
    }
}