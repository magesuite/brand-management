<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Search;

class Suggest extends \Magento\Framework\View\Element\Template
{
    protected ?\MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\Collection $collection = null;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        protected \Magento\Search\Model\QueryFactory $queryFactory,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\CollectionFactory $collectionFactory,
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function canShowBlock(): bool
    {
        return $this->getResultsPageSize() > 0 && $this->getResultCount() > 0;
    }

    /**
     * @return \MageSuite\BrandManagement\Model\Brands[]
     */
    public function getItems(): array
    {
        return $this->getCollection()->getItems();
    }

    public function getCollection(): \MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\Collection
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->addAttributeToSelect('*');
            $this->collection->setPageSize($this->getResultsPageSize());
            $this->collection->addSearchFilter($this->getQueryText());
        }

        return $this->collection;
    }

    public function getResultsPageSize(): int
    {
        return $this->configuration->getSearchMaxResult();
    }

    public function getResultCount(): int
    {
        return (int) $this->getCollection()->getSize();
    }

    public function getQueryText(): string
    {
        return $this->queryFactory->get()->getQueryText();
    }

    public function getShowAllUrl(): string
    {
        return $this->getUrl('brands/index/all');
    }
}
