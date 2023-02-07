<?php

namespace MageSuite\BrandManagement\Service\Upgrade;

class Migration
{
    const COLLECTION_PAGE_SIZE = 100;

    protected \Magento\Framework\App\State $state;

    protected \Magento\Store\Model\StoreManager $storeManager;

    protected \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory;

    protected \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResource;

    protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository;

    protected \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfigurationMapper;

    protected \MageSuite\ContentConstructorAdmin\Repository\Xml\ComponentConfigurationToXmlMapper $componentConfigurationToXmlMapper;

    protected \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Store\Model\StoreManager $storeManager,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResource,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfigurationMapper,
        \MageSuite\ContentConstructorAdmin\Repository\Xml\ComponentConfigurationToXmlMapper $componentConfigurationToXmlMapper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->state = $state;
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
        $this->brandsResource = $brandsResource;
        $this->brandsRepository = $brandsRepository;
        $this->xmlToComponentConfigurationMapper = $xmlToComponentConfigurationMapper;
        $this->componentConfigurationToXmlMapper = $componentConfigurationToXmlMapper;
        $this->logger = $logger;
    }

    public function transferOldXmlValuesToNewJsonFields(): void
    {
        $storeIds = $this->getStoresIds();
        $collection = $this->collectionFactory->create();

        $this->state->emulateAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML, function () use ($storeIds, $collection) { //phpcs:ignore
            foreach ($storeIds as $storeId) {
                $this->storeManager->setCurrentStore($storeId);

                $collectionWithFilters = clone $collection;
                $collectionWithFilters->setStoreId($storeId)->addAttributeToSelect('*');
                $collectionWithFilters->addAttributeToFilter("layout_update_xml", ["notnull" => true], "left");

                $this->doNotAllowDefaultValue($collectionWithFilters);
                $this->setJsonValueForItemsInCollection($collectionWithFilters, $storeId);
            }
        });
    }

    protected function setJsonValueForItemsInCollection($collection, $storeId)
    {
        $portionNumber = 0;
        while ($items = $this->getItemsPortion($collection, ++$portionNumber)) {
            $this->transferXmlContentToJson($items, $storeId);
        }
    }

    protected function getItemsPortion($collection, int $currentPage)
    {
        if ($currentPage > ceil($collection->getSize() / self::COLLECTION_PAGE_SIZE)) {
            return [];
        }

        return $collection->setPageSize(self::COLLECTION_PAGE_SIZE)->setCurPage($currentPage)->getItems();
    }

    protected function transferXmlContentToJson($items, $storeId): void
    {
        foreach ($items as $item) {
            $layoutUpdateXml = $item->getLayoutUpdateXml();

            if ($this->isAlreadyMigrated($item, $storeId)) {
                continue;
            }

            $item->setLayoutUpdateXmlBackup($layoutUpdateXml);

            $cleanedXml = $this->componentConfigurationToXmlMapper->cleanXml($layoutUpdateXml);
            $components = $this->xmlToComponentConfigurationMapper->map($layoutUpdateXml);

            $item->setLayoutUpdateXml($cleanedXml);

            if (!empty($components)) {
                $item->setContentConstructorContent(json_encode($components));
            }

            try {
                $item->setStoreId($storeId);
                $this->brandsRepository->save($item);
            } catch (\Exception $e) {
                $message = sprintf('Failed migration of %s, id: %s, error: %s', get_class($item), $item->getId(), $e->getMessage());
                $this->logger->error($message);
            }
        }
    }

    protected function getStoresIds(): array
    {
        $result = [0];

        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            $result[] = $store->getId();
        }

        return $result;
    }

    protected function doNotAllowDefaultValue(&$collection)
    {
        $exprLayoutUpdateXml = new \Zend_Db_Expr("at_layout_update_xml.value IS NOT NULL");

        $collection->getSelect()
            ->reset(\Magento\Framework\DB\Select::WHERE)
            ->where($exprLayoutUpdateXml);
    }

    protected function isAlreadyMigrated($item, $storeId): bool
    {
        $layoutUpdateXmlBackup = $this->brandsResource->getAttributeRawValue($item->getId(), 'layout_update_xml_backup', $storeId);
        return !empty($layoutUpdateXmlBackup);
    }
}
