<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Plugin\Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class EnableWysiwygForSafetyRegulations
{
    public function __construct(
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
    ) {
    }

    public function afterGetIsWysiwygEnabled(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject, $result) {
        return $this->processResult($subject, $result);
    }

    public function afterGetIsHtmlAllowedOnFront(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject, $result) {
        return $this->processResult($subject, $result);
    }

    protected function processResult(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject, $result) {
        if (!$subject->getAttributeCode() === \MageSuite\BrandManagement\Setup\Patch\Data\AddSafetyRegulationsAttribute::ATTRIBUTE_CODE) {
            return $result;
        }

        return $this->configuration->isWysiwygForSafetyRegulationsEnabled() ? 1 : $result;
    }
}
