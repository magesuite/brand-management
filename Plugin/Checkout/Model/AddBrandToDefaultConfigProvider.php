<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Plugin\Checkout\Model;

class AddBrandToDefaultConfigProvider
{
    public function __construct(protected \MageSuite\BrandManagement\Helper\Configuration $configuration) {}

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result): array
    {
        if (!$this->configuration->isVisibleOnOrderSummary()) {
            return $result;
        }

        if (empty($result['quoteData']['items'])) {
            return $result;
        }

        foreach ($result['quoteData']['items'] as $i => $item) {
            $product = $item->getProduct();

            if (!$product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
                continue;
            }

            if (empty($product->getData(\MageSuite\BrandManagement\Model\Brands::BRAND_ATTRIBUTE_CODE))) {
                continue;
            }

            $result['totalsData']['items'][$i]['product_brand'] = $product->getAttributeText(\MageSuite\BrandManagement\Model\Brands::BRAND_ATTRIBUTE_CODE);
            $result['quoteItemData'][$i]['product_brand'] = $product->getAttributeText(\MageSuite\BrandManagement\Model\Brands::BRAND_ATTRIBUTE_CODE);
        }

        return $result;
    }
}
