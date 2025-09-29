<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\Modifier;

class AttributeSetId extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    protected int $attributeSetId;

    public function __construct(
        protected \MageSuite\BrandManagement\Model\GetDefaultAttributeSetId $getDefaultAttributeSetId,
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData $requestData,
    ) {}

    public function modifyData(array $data): array
    {
        $brandId = $this->requestData->getBrandId();

        $attributeSetId = $this->getDefaultAttributeSetId->execute();
        $data[$brandId][\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE]['attribute_set_id'] = $attributeSetId;

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
