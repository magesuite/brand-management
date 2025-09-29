<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\ViewModel\Category;

class Headline implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        protected \Magento\Framework\Registry $registry
    ) {}

    public function shouldHeadlineBeHidden(): bool
    {
        $currentBrand = $this->registry->registry('current_brand');

        if (empty($currentBrand)) {
            return false;
        }

        return (bool)$currentBrand->getData(\MageSuite\BrandManagement\Setup\Patch\Data\AddBrandHideTitleAttribute::ATTRIBUTE_CODE_HIDE_HEADLINE);
    }
}
