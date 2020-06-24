<?php
namespace MageSuite\BrandManagement\Plugin\Smile\ElasticsuiteCatalog\Model\Autocomplete\Product\ItemFactory;

class AddBrandNameToSearchFlyout
{
    public function afterCreate(\Smile\ElasticsuiteCatalog\Model\Autocomplete\Product\ItemFactory $subject, $result, array $data)
    {
        $product = $data['product'];
        $brandName = $this->getBrandName($product->getDocumentSource());

        if (!empty($brandName)) {
            $result->setData('brand', $brandName);
        }

        return $result;
    }

    protected function getBrandName($productDocumentSource)
    {
        if (!empty($productDocumentSource['option_text_brand'])) {
            return $productDocumentSource['option_text_brand'][0];
        }
        return '';
    }
}
