<?php
namespace MageSuite\BrandManagement\Plugin\Smile\ElasticsuiteCatalog\Model\Autocomplete\Product\ItemFactory;

class AddBrandNameToSearchFlyout
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function afterCreate(\Smile\ElasticsuiteCatalog\Model\Autocomplete\Product\ItemFactory $subject, $result, array $data)
    {
        if (!$this->configuration->isVisible(\MageSuite\BrandManagement\Helper\Configuration::BRAND_VISIBILITY_SEARCH_AUTOCOMPLETE)) {
            return $result;
        }

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
