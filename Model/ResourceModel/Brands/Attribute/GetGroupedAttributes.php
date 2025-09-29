<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands\Attribute;

class GetGroupedAttributes
{
    public function __construct(
        protected \Magento\Eav\Model\Entity\TypeFactory $entityTypeFactory,
        protected \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $attributeCollectionFactory,
        protected \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $attributeGroupCollectionFactory,
    ) {}

    public function execute(): array
    {
        $entityType = $this->entityTypeFactory->create()->loadByCode(\MageSuite\BrandManagement\Model\Brands::ENTITY);
        $defaultAttributeSetId = $entityType->getDefaultAttributeSetId();

        $result = [];

        $groupCollection = $this->attributeGroupCollectionFactory->create();
        $groupCollection->setAttributeSetFilter($defaultAttributeSetId);
        $groupCollection->setOrder('sort_order', 'ASC');

        foreach ($groupCollection as $group) {
            $groupId = $group->getId();
            $groupName = $group->getAttributeGroupName();

            $attributeCollection = $this->attributeCollectionFactory->create();
            $attributeCollection->setAttributeSetFilter($defaultAttributeSetId);
            $attributeCollection->setAttributeGroupFilter($groupId);

            foreach ($attributeCollection as $attribute) {
                $result[$groupName][] = $attribute->getAttributeCode();
            }
        }

        return $result;
    }
}
