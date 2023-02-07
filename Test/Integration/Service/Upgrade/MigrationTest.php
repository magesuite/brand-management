<?php

namespace MageSuite\BrandManagement\Test\Integration\Service\Upgrade;

class MigrationTest extends \MageSuite\ContentConstructorAdmin\Test\Integration\Upgrade\AbstractMigrationTestCase
{
    protected ?\MageSuite\BrandManagement\Model\BrandsRepository $brandsRepository;

    protected ?\MageSuite\BrandManagement\Service\Upgrade\Migration $brandMigration;

    public function setUp(): void
    {
        parent::setUp();

        $this->brandsRepository = $this->objectManager->get(\MageSuite\BrandManagement\Model\BrandsRepository::class);
        $this->brandMigration = $this->objectManager->get(\MageSuite\BrandManagement\Service\Upgrade\Migration::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     *
     * @magentoDataFixture loadWebsiteAndStoresFixture
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brand_migration.php
     */
    public function testProductsMigrationOnUpgrade()
    {
        $store1 = $this->storeRepository->get("store_for_eu_website");
        $store2 = $this->storeRepository->get("store_for_us_website");
        $brandsAndStores =
            [
                self::ALL_STORE_VIEWS => [101],
                $store1->getId() => [102],
                $store2->getId() => [103]
            ];

        foreach ($brandsAndStores as $storeId => $brandIds) {
            $layoutUpdate = $this->getXmlInputStringForStoreId($storeId);

            foreach ($brandIds as $brandId) {
                $brand = $this->brandsRepository->getById($brandId, $storeId);
                $brand->setLayoutUpdateXmlBackup('');
                $brand->setContentConstructorContent('');
                $brand->setLayoutUpdateXml($layoutUpdate);

                $brand->setEntityId($brand->getId());
                $brand->setStoreId($storeId);
                $this->brandsRepository->update($brand);
            }
        }

        $this->brandMigration->transferOldXmlValuesToNewJsonFields();
        $this->runAssertions($brandsAndStores, $this->brandsRepository, 100);
    }

    protected function getItemFromRepository($repository, $id, $storeId)
    {
        return $repository->getById($id, $storeId);
    }
}
