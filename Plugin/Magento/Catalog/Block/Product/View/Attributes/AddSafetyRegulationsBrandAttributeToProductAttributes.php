<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Plugin\Magento\Catalog\Block\Product\View\Attributes;

class AddSafetyRegulationsBrandAttributeToProductAttributes
{
    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandRepository,
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        protected \Magento\Framework\Escaper $escaper
    ) {}

    public function afterGetAdditionalData(
        \Magento\Catalog\Block\Product\View\Attributes $subject,
        array $result
    ): array {
        if (!$this->configuration->isShowSafetyRegulationsEnabled()) {
            return $result;
        }

        $product = $subject->getProduct();
        $brandId = $product->getBrand();

        if (empty($brandId)) {
            return $result;
        }

        $brand = $this->brandRepository->getById($brandId, $product->getStoreId());

        $safetyRegulations = $brand->getData(\MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::ATTRIBUTE_CODE);

        if (empty($safetyRegulations)) {
            return $result;
        }

        if (!$this->configuration->isWysiwygForSafetyRegulationsEnabled()) {
            $safetyRegulations = nl2br($safetyRegulations);
        }

        $result[\MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::ATTRIBUTE_CODE] = [
            'label' => __(\MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::ATTRIBUTE_LABEL),
            'value' => $safetyRegulations,
            'code' => \MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::ATTRIBUTE_CODE,
        ];

        return $result;
    }
}
