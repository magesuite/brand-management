<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Api;

interface BrandAttributeRepositoryInterface
{
    public function getList(?\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null): \Magento\Eav\Api\Data\AttributeSearchResultsInterface;

    public function getListByCodes(array $attributeCodes): \Magento\Eav\Api\Data\AttributeSearchResultsInterface;

    public function getListByAttributeProperty(string $property, mixed $value, string $condition = 'eq'): \Magento\Eav\Api\Data\AttributeSearchResultsInterface;
}
