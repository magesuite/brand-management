<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Checkout\Cart\Item\Renderer;

class Brand extends \Magento\Framework\View\Element\Template
{
    public function getProduct(): ?\Magento\Catalog\Model\Product
    {
        $parentBlock = $this->getParentBlock();

        if ($parentBlock instanceof \Magento\Checkout\Block\Cart\Item\Renderer) {
            return $parentBlock->getProduct();
        }

        return null;
    }
}
