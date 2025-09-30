<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\ViewModel;

class BrandRenderer implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
    ) {}

    public function getFirstBrand(\Magento\Catalog\Api\Data\ProductInterface $product): ?\MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $brands = $product->getData('brand');
        $storeId = $product->getStoreId();

        if (empty($brands)) {
            return null;
        }

        $brandsIds = explode(',', $brands);
        $brandId = current($brandsIds);

        if (!$brandId) {
            return null;
        }

        return $this->brandsRepository->getById((int)$brandId, $storeId);
    }

    public function getBrandName(?\Magento\Catalog\Api\Data\ProductInterface $product, string $location): string
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

        return (string)$product->getAttributeText('brand');
    }

    public function isVisible(string $location): bool
    {
        return $this->configuration->isVisible($location);
    }
}
