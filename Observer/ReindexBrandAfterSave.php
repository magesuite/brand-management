<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Observer;

class ReindexBrandAfterSave implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        protected \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
    ) {
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        /** @var \MageSuite\BrandManagement\Model\Brands $object */
        $object = $observer->getData('data_object');
        $indexer = $this->indexerRegistry->get(\MageSuite\BrandManagement\Model\Brands\Indexer\Fulltext::INDEXER_ID);

        if (!$indexer->isScheduled()) {
            $indexer->reindexRow((int) $object->getEntityId());
        }
    }
}
