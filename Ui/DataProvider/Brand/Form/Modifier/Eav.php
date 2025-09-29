<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\Modifier;

class Eav extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    public const CONTENT_CONSTRUCTOR_FRONTEND_INPUT = 'content_constructor';

    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandAttributeRepositoryInterface $brandAttributeRepository,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\Attribute\GetGroupedAttributes $getGroupedAttributes,
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\InputConfig $inputConfig,
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData $requestData,
        protected \Magento\Catalog\Model\Attribute\ScopeOverriddenValue $scopeOverriddenValue,
    ) {}

    public function modifyData(array $data): array
    {
        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        $attributeGroups = $this->getGroupedAttributes->execute();

        $groupSortOrder = 0;

        foreach ($attributeGroups as $groupName => $attributeCodes) {
            $attributes = $this->brandAttributeRepository->getListByCodes($attributeCodes)->getItems();

            $groupMeta = $this->getGroupMeta($attributes);

            $groupIdentifier = strtolower(str_replace(' ', '_', $groupName));
            $meta[$groupIdentifier] = [
                'children' => $groupMeta,
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => 'fieldset',
                            'sortOrder' => $groupSortOrder,
                            'dataScope' => sprintf('data.%s', \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE),
                            'label' => $groupName,
                            'collapsible' => true,
                            'opened' => true,
                        ],
                    ],
                ],
            ];

            $groupSortOrder += 10;
        }


        return $meta;
    }

    public function getGroupMeta(array $attributes): array
    {
        $groupMeta = [];
        $attributeSortOrder = 0;

        foreach ($attributes as $attribute) {
            if ($attribute->getFrontendInput() === self::CONTENT_CONSTRUCTOR_FRONTEND_INPUT) {
                continue;
            }

            $attributeCode = $attribute->getAttributeCode();

            $groupMeta['container_' . $attributeCode] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'required' => $attribute->getIsRequired(),
                            'sortOrder' => $attributeSortOrder,
                        ],
                    ],
                ],
                'children' => [
                    $attributeCode => [
                        'arguments' => [
                            'data' => [
                                'config' => $this->getAttributeConfig($attribute, $attributeSortOrder),
                            ],
                        ],
                    ],
                ],
            ];

            $attributeSortOrder += 10;
        }

        return $groupMeta;
    }

    public function getAttributeConfig(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute, int $sortOrder): array
    {
        $basicConfig = [
            'visible' => 1,
            'required' => (int)$attribute->getIsRequired(),
            'notice' => $attribute->getNote() === null ? null : __($attribute->getNote()),
            'label' => __($attribute->getDefaultFrontendLabel()),
            'code' => $attribute->getAttributeCode(),
            'scopeLabel' => $this->getScopeLabel($attribute),
            'sortOrder' => $sortOrder,
            'componentType' => 'field',
            'globalScope' => $attribute->getScope() == \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
        ];

        $inputConfig = $this->getInputConfig($attribute);

        $config = array_merge($basicConfig, $inputConfig);
        $config = $this->addUseDefaultCheckbox($attribute, $config);

        return $config;
    }

    public function getInputConfig(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        $frontendInput = $attribute->getFrontendInput();

        return match ($frontendInput) {
            'boolean' => $this->inputConfig->getBoolean($attribute),
            'select' => $this->inputConfig->getSelect($attribute),
            'textarea' => $this->inputConfig->getTextArea($attribute),
            'image' => $this->inputConfig->getImage($attribute),
            default => $this->inputConfig->getDefault($attribute),
        };
    }

    protected function getScopeLabel(\MageSuite\BrandManagement\Model\ResourceModel\Eav\Attribute $attribute): \Magento\Framework\Phrase
    {
        $scope = $attribute->getScope();

        return match ($scope) {
            \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE => __('[STORE VIEW]'),
            \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE => __('[WEBSITE]'),
            default => __('[GLOBAL]'),
        };
    }

    protected function addUseDefaultCheckbox(\MageSuite\BrandManagement\Model\ResourceModel\Eav\Attribute $attribute, array $config): array
    {
        $storeId = $this->requestData->getStoreId();

        if ($storeId === \Magento\Store\Model\Store::DEFAULT_STORE_ID || $attribute->isScopeGlobal()) {
            return $config;
        }

        $brand = $this->requestData->getBrand();
        $isOverwritten = $this->scopeOverriddenValue->containsValue(
            \MageSuite\BrandManagement\Api\Data\BrandsInterface::class,
            $brand,
            $attribute->getAttributeCode(),
            $storeId
        );

        $config['service'] = ['template' => 'ui/form/element/helper/service'];

        if ($isOverwritten) {
            return $config;
        }

        $config['disabled'] = true;

        return $config;
    }
}
