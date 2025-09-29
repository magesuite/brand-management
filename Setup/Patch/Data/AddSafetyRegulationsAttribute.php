<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Setup\Patch\Data;

class AddSafetyRegulationsAttribute implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'safety_regulations';
    public const ATTRIBUTE_LABEL = 'Safety Regulations';

    protected \Magento\Eav\Setup\EavSetup $eavSetup;

    public function __construct(
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetup = $eavSetupFactory->create();
    }

    public function apply(): void
    {
        $this->eavSetup->addAttribute(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'text',
                'label' => self::ATTRIBUTE_LABEL,
                'input' => 'text',
                'required' => false,
                'sort_order' => 100,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
            ]
        );
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
