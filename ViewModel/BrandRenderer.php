<?php
namespace MageSuite\BrandManagement\ViewModel;

class BrandRenderer implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    public function __construct(
        \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
    )
    {
        $this->configuration = $configuration;
        $this->brandsRepository = $brandsRepository;
    }

    public function getFirstBrand(\Magento\Catalog\Api\Data\ProductInterface $product): ?\MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $brands = $product->getData('brand');
        $storeId = $product->getStoreId();

        if (empty($brands)) {
            return null;
        }

        $brandsIds = explode(',', $brands);
        $brandId = reset($brandsIds);

        if (empty($brandId)) {
            return null;
        }

        return $this->brandsRepository->getById($brandId, $storeId);
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
