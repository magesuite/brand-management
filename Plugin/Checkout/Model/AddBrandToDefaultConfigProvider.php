<?php
namespace MageSuite\BrandManagement\Plugin\Checkout\Model;

class AddBrandToDefaultConfigProvider
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {
        if (!$this->configuration->isVisible(\MageSuite\BrandManagement\Helper\Configuration::BRAND_VISIBILITY_ORDER_SUMMARY)) {
            return $result;
        }
        if (empty($result['quoteData']['items'])) {
            return $result;
        }
        foreach ($result['quoteData']['items'] as $i => $item) {
            $product = $item->getProduct();
            $class = get_class($product);
            if (!$product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
                continue;
            }
            if (empty($product->getData(\MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE))) {
                continue;
            }
            $result['totalsData']['items'][$i]['product_brand'] = $product->getAttributeText(\MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE);
            $result['quoteItemData'][$i]['product_brand'] = $product->getAttributeText(\MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE);
        }
        return $result;
    }
}
