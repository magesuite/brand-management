<?php
namespace MageSuite\BrandManagement\ViewModel;

class BrandRenderer implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getBrandName($product, $location)
    {
        if (!$this->isVisible($location)) {
            return '';
        }

        if (!$product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
            return '';
        }

        if (empty($product->getData('brand'))) {
            return '';
        }

        return $product->getAttributeText('brand');
    }

    public function isVisible($location)
    {
        return $this->configuration->isVisible($location);
    }
}
