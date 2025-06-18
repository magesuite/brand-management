<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Autocomplete\Brand;

class DataProvider implements \Magento\Search\Model\Autocomplete\DataProviderInterface
{
    public const AUTOCOMPLETE_TYPE = 'brand';

    protected ?\MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\Collection $collection = null;

    public function __construct(
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\CollectionFactory $collectionFactory,
        protected \MageSuite\BrandManagement\Model\Autocomplete\Brand\ItemFactory $itemFactory,
        protected \Magento\Search\Model\QueryFactory $queryFactory,
        protected \Smile\ElasticsuiteCatalog\Helper\Autocomplete $autocompleteHelper,
        protected \Smile\ElasticsuiteCore\Model\Autocomplete\Terms\DataProvider $termDataProvider,
        protected string $type = self::AUTOCOMPLETE_TYPE,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): array
    {
        $items = [];
        /** @var \MageSuite\BrandManagement\Model\Brands $item */
        foreach ($this->getCollection() as $item) {
            $items[] = $this->itemFactory->create([
                'name' => $item->getBrandName(),
                'url'   => $item->getBrandUrl(),
                'type' => $this->getType(),
            ]);
        }

        return $items;
    }

    public function getCollection(): \MageSuite\BrandManagement\Model\ResourceModel\Brands\Fulltext\Collection
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->addAttributeToSelect('*');
            $this->collection->setPageSize($this->getResultsPageSize());

            $terms = $this->getSuggestedTerms();
            $terms[] = $this->getQueryText();

            $this->collection->addSearchFilter($terms);
        }

        return $this->collection;
    }

    protected function getSuggestedTerms(): array
    {
        $result = [];
        /** @var \Magento\Search\Model\Autocomplete\Item $item */
        foreach ($this->termDataProvider->getItems() as $item) {
            $result[] = $item->getTitle();
        }

        return $result;
    }

    protected function getResultsPageSize(): int
    {
        return (int) $this->autocompleteHelper->getMaxSize($this->getType());
    }

    protected function getQueryText(): string
    {
        return $this->queryFactory->get()->getQueryText();
    }
}
