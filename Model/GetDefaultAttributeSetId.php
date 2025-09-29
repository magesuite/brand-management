<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

class GetDefaultAttributeSetId
{
    protected int $attributeSetId;

    public function __construct(
        protected \Magento\Eav\Model\Config $eavConfig,
    ) {}

    public function execute(): int
    {
        if (!isset($this->attributeSetId)) {
            $entityType = $this->eavConfig->getEntityType(\MageSuite\BrandManagement\Model\Brands::ENTITY);
            $this->attributeSetId = (int)$entityType->getDefaultAttributeSetId();
        }

        return $this->attributeSetId;
    }
}
