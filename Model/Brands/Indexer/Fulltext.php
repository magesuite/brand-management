<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Brands\Indexer;

class Fulltext implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    public const INDEXER_ID = 'elasticsuite_brand_fulltext';

    public function __construct(
        protected \Magento\Framework\Indexer\SaveHandler\IndexerInterface $indexerHandler,
        protected \Magento\Framework\Search\Request\DimensionFactory $dimensionFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\Indexer\Fulltext $resourceModel,
    ) {
    }

    public function execute(mixed $ids): void
    {
        $storeIds = array_keys($this->storeManager->getStores());

        foreach ($storeIds as $storeId) {
            $dimension = $this->dimensionFactory->create(['name' => 'scope', 'value' => $storeId]);
            $this->indexerHandler->deleteIndex([$dimension], new \ArrayObject($ids)); // phpcs:ignore
            $this->indexerHandler->saveIndex([$dimension], $this->rebuildStoreIndex($storeId, $ids));
        }
    }

    public function executeFull(): void
    {
        $storeIds = array_keys($this->storeManager->getStores());

        foreach ($storeIds as $storeId) {
            $dimension = $this->dimensionFactory->create(['name' => 'scope', 'value' => $storeId]);
            $this->indexerHandler->cleanIndex([$dimension]);
            $this->indexerHandler->saveIndex([$dimension], $this->rebuildStoreIndex($storeId));
        }
    }

    public function executeList(array $ids): void
    {
        $this->execute($ids);
    }

    public function executeRow(mixed $id): void
    {
        $this->execute([$id]);
    }

    protected function rebuildStoreIndex(int $storeId, ?array $entityIds = null): \Traversable
    {
        $fromId = 0;

        do {
            $data = $this->resourceModel->getSearchableBrandsData($storeId, $entityIds, $fromId);

            foreach ($data as $item) {
                $fromId = (int) $item['entity_id'];

                yield $fromId => $item;
            }
        } while (!empty($data));
    }
}
