<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\AttributeConfig;

interface AttributeConfigProviderInterface
{
    public function getConfig(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute): array;
}
