<?php
namespace MageSuite\BrandManagement\Plugin\Checkout\Model;

class AddBrandToDefaultConfigProvider
{
    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {
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
