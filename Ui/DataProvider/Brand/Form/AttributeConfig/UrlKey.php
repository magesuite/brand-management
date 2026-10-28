<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\AttributeConfig;

class UrlKey implements \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\AttributeConfig\AttributeConfigProviderInterface
{
    public const COMPONENT = 'MageSuite_BrandManagement/js/form/element/url-key';
    public const SOURCE_ATTRIBUTE_CODE = 'brand_name';

    public function __construct(
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData $requestData,
    ) {}

    public function getConfig(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'component' => self::COMPONENT,
            'mask' => sprintf('{{%s}}', self::SOURCE_ATTRIBUTE_CODE),
            'allowImport' => $this->requestData->getBrandId() === null,
        ];
    }
}
