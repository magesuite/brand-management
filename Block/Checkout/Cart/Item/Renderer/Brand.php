<?php
namespace MageSuite\BrandManagement\Block\Checkout\Cart\Item\Renderer;

class Brand extends \Magento\Framework\View\Element\Template
{
    public function getProduct()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock instanceof \Magento\Checkout\Block\Cart\Item\Renderer) {
            return $parentBlock->getProduct();
        }
        return null;
    }
}
