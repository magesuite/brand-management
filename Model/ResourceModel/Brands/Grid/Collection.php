<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands\Grid;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsCollectionFactory;
    protected \Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool $filterPool;
    protected \Magento\Framework\Api\Search\SearchResultInterfaceFactory $searchResultFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\Api\Search\ReportingInterface $reporting,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsCollectionFactory,
        \Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool $filterPool,
        \Magento\Framework\Api\Search\SearchResultInterfaceFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->brandsCollectionFactory = $brandsCollectionFactory;
        $this->filterPool = $filterPool;
        $this->searchResultFactory = $searchResultFactory;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $reporting, $searchCriteriaBuilder, $request, $filterBuilder, $meta, $data);
    }

    public function getData(): array
    {
        $searchResult = $this->getSearchResult();

        $items = $searchResult->getItems();

        return [
            'items' => $items,
            'totalRecords' => $searchResult->getTotalCount(),
        ];
    }

    public function getSearchResult(): \Magento\Framework\Api\Search\SearchResultInterface
    {
        $collection = $this->brandsCollectionFactory->create();
        $totalCount = $collection->getSize();

        $collection->addAttributeToSelect('*');

        $searchCriteria = $this->getSearchCriteria();
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $this->filterPool->applyFilters($collection, $searchCriteria);

        foreach ($searchCriteria->getSortOrders() as $sortOrder) {
            if ($sortOrder->getField()) {
                $collection->setOrder($sortOrder->getField(), $sortOrder->getDirection());
            }
        }

        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $items = [];

        foreach ($collection->getItems() as $item) {
            $data = $item->getData();
            $data['id_field_name'] = 'entity_id';

            $items[] = $data;
        }

        $searchResult->setItems($items);
        $searchResult->setTotalCount($totalCount);

        return $searchResult;
    }
}
