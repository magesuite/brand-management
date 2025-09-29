<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\Modifier;

class Image extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandAttributeRepositoryInterface $brandAttributeRepository,
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData $requestData,
        protected \Magento\Eav\Model\Config $eavConfig,
        protected \Magento\Framework\UrlInterface $urlBuilder,
        protected \Magento\MediaGalleryApi\Api\GetAssetsByPathsInterface $getAssetsByPaths,
    ) {}

    public function modifyData(array $data): array
    {
        $brandId = $this->requestData->getBrandId();
        $imageAttributes = $this->getImageAttributes();

        foreach ($imageAttributes as $attributeCode) {
            $image = $data[$brandId][\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE][$attributeCode] ?? null;

            if (!is_string($image)) {
                continue;
            }

            $imageData = $this->getImageData($image);
            $data[$brandId][\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE][$attributeCode] = $imageData;
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    protected function getImageAttributes(): array
    {
        $searchResult = $this->brandAttributeRepository->getListByAttributeProperty('frontend_input', 'image');

        return array_map(
            fn($attribute) => $attribute->getAttributeCode(),
            $searchResult->getItems()
        );
    }

    protected function getImageData(string $imagePath): array
    {
        $assets = $this->getAssetsByPaths->execute([$imagePath]);

        if (empty($assets)) {
            return [];
        }

        $asset = current($assets);

        $mediaUrl = $this->urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);
        $mediaUrl = rtrim($mediaUrl, '/');

        $imageUrl = sprintf('%s/%s', $mediaUrl, $asset->getPath());

        return [
            [
                'type' => $asset->getContentType(),
                'name' => $asset->getTitle(),
                'size' => $asset->getSize(),
                'url' => $imageUrl,
            ],
        ];
    }
}
