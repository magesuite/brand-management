<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form;

class InputConfig
{
    public function getBoolean(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'dataType' => 'boolean',
            'formElement' => 'checkbox',
            'prefer' => 'toggle',
            'default' => $attribute->getDefaultValue(),
            'valueMap' => [
                'true' => '1',
                'false' => '0',
            ],
        ];
    }

    public function getSelect(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'dataType' => 'select',
            'formElement' => 'select',
            'options' => $attribute->getSource()?->toOptionArray() ?? [],
        ];
    }

    public function getTextArea(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'dataType' => 'text',
            'formElement' => 'textarea',
        ];
    }

    public function getImage(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'formElement' => 'imageUploader',
            'componentType' => 'imageUploader',
            'notice' => 'Allowed file types: png, gif, jpg, jpeg, svg.',
            'imageSize' => '1048576',
            'allowed_extensions' => 'jpg jpeg gif png svg',
            'dataScope' => $attribute->getAttributeCode(),
            'previewTmpl' => 'Magento_Catalog/image-preview',
            'uploaderConfig' => [
                'url' => 'brands/brand/newImage',
            ],
        ];
    }

    public function getDefault(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array
    {
        return [
            'dataType' => 'text',
            'formElement' => 'input',
        ];
    }
}
