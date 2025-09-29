<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

class BrandAttributeRepository implements \MageSuite\BrandManagement\Api\BrandAttributeRepositoryInterface
{
    public function __construct(
        protected \Magento\Eav\Model\AttributeRepository $eavAttributeRepository,
        protected \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    ) {}

    public function getList(?\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null): \Magento\Eav\Api\Data\AttributeSearchResultsInterface
    {
        $searchCriteria ??= $this->searchCriteriaBuilder->create();

        return $this->eavAttributeRepository->getList(
            \MageSuite\BrandManagement\Model\Brands::ENTITY,
            $searchCriteria
        );
    }

    public function getListByCodes(array $attributeCodes): \Magento\Eav\Api\Data\AttributeSearchResultsInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('attribute_code', $attributeCodes, 'in')->create();

        return $this->getList($searchCriteria);
    }

    public function getListByAttributeProperty(string $property, mixed $value, string $condition = 'eq'): \Magento\Eav\Api\Data\AttributeSearchResultsInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter($property, $value, $condition)->create();

        return $this->getList($searchCriteria);
    }
}
