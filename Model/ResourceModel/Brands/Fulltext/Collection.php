<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext;

class Collection extends \MageSuite\BrandManagement\Model\ResourceModel\Brands\Collection
{
    /** @var \Smile\ElasticsuiteCore\Search\Adapter\Elasticsuite\Response\QueryResponse|null  */
    protected ?\Magento\Framework\Search\ResponseInterface $queryResponse = null;

    protected mixed $queryText = null;

    protected array $filters = [];

    protected array $facets = [];

    protected ?int $storeId = null;

    protected string $searchRequestName = 'brand_search_container';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Smile\ElasticsuiteCore\Search\Request\Builder $requestBuilder,
        protected \Magento\Search\Model\SearchEngine $searchEngine,
        ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $connection,
        );
    }

    public function getSize(): int
    {
        if ($this->_totalRecords === null) {
            $this->loadCollectionSize();
        }

        return $this->_totalRecords;
    }

    public function addSearchFilter(mixed $query): self
    {
        $this->queryText = $query;

        return $this;
    }

    public function addFieldToFilter(mixed $attribute, mixed $condition = null): self
    {
        $this->filters[$attribute] = $condition;

        return $this;
    }

    protected function _renderFiltersBefore(): void
    {
        $searchRequest = $this->prepareRequest();
        $this->queryResponse = $this->searchEngine->search($searchRequest);
        $this->_totalRecords = $this->queryResponse->count();

        $entityIds = [0];
        $documents = $this->queryResponse->getIterator()->getArrayCopy();

        /** @var \Magento\Framework\Api\Search\DocumentInterface $document */
        foreach ($documents as $document) {
            $entityIds[] = (int) $document->getId();
        }

        $this->getSelect()->where('e.entity_id IN (?)', ['in' => $entityIds]);
        $this->_pageSize = false;

        parent::_renderFiltersBefore();
    }

    protected function _afterLoad(): self
    {
        // Resort items according the search response.
        /** @var \MageSuite\BrandManagement\Model\Brands[] $originalItems */
        $originalItems = $this->_items;
        $this->_items = [];
        $storeId = $this->getStoreId();

        foreach ($this->queryResponse->getIterator() as $document) {
            $documentId = $document->getId();

            if (isset($originalItems[$documentId])) {
                $this->_items[$documentId] = $originalItems[$documentId];
                $this->_items[$documentId]->setStoreId($storeId);
            }
        }

        return parent::_afterLoad();
    }

    protected function prepareRequest(): \Smile\ElasticsuiteCore\Search\RequestInterface
    {
        // Pagination params.
        $size = $this->_pageSize ?: 20;
        $from = $size * (max(1, $this->_curPage) - 1);

        $sortOrders = $this->prepareSortOrders();

        $searchRequest = $this->requestBuilder->create(
            $this->getStoreId(),
            $this->searchRequestName,
            $from,
            $size,
            $this->queryText,
            $sortOrders,
            $this->filters,
            $this->facets
        );

        return $searchRequest;
    }

    protected function prepareSortOrders(): array
    {
        $sortOrders = [];

        foreach ($this->_orders as $attribute => $direction) {
            $sortParams = ['direction' => $direction];
            $sortField = $this->mapFieldName($attribute);
            $sortOrders[$sortField] = $sortParams;
        }

        return $sortOrders;
    }

    protected function mapFieldName(string $fieldName): string
    {
        if (isset($this->fieldNameMapping[$fieldName])) {
            $fieldName = $this->fieldNameMapping[$fieldName];
        }

        return $fieldName;
    }

    protected function loadCollectionSize(): void
    {
        $searchRequest = $this->requestBuilder->create(
            $this->getStoreId(),
            $this->searchRequestName,
            0,
            0,
            $this->queryText,
            [],
            $this->filters
        );
        $searchResponse = $this->searchEngine->search($searchRequest);
        $this->_totalRecords = $searchResponse->count();
    }
}
