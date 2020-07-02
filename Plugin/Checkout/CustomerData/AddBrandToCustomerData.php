<?php
namespace MageSuite\BrandManagement\Plugin\Checkout\CustomerData;

class AddBrandToCustomerData
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function afterGetItemData(\Magento\Checkout\CustomerData\DefaultItem $subject, array $result, \Magento\Quote\Model\Quote\Item $item)
    {
        if (!$this->configuration->isVisibleOnMiniCart()) {
            return $result;
        }

        $result['product_brand'] = '';
        $product = $item->getProduct();
        if (!$product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
            return $result;
        }
        if (empty($product->getData(\MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE))) {
            return $result;
        }
        $result['product_brand'] = $product->getAttributeText(\MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE);
        return $result;
    }
}
