<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Model\ResourceModel;

class BrandsTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository = null;
    protected ?\MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandRepository = null;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->brandRepository = $objectManager->get(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class);

        parent::setUp();
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/multiple_products.php
     */
    public function testIfBrandOptionsWereCleared(): void
    {
        foreach ($this->getProductBrands() as $productSku => $brand) {
            $product = $this->productRepository->get($productSku);
            $this->assertEquals($brand, $product->getBrand());
        }

        $this->brandRepository->deleteById(40);
        $this->productRepository->cleanCache();

        foreach ($this->getClearedProductBrands() as $productSku => $brand) {
            $product = $this->productRepository->get($productSku);
            $this->assertEquals($brand, $product->getBrand());
        }
    }

    protected function getProductBrands(): array
    {
        return [
            'simple1' => '40',
            'simple2' => '40,4040',
            'simple3' => '4040,40',
            'simple4' => '4040,40,4050'
        ];
    }

    protected function getClearedProductBrands(): array
    {
        return [
            'simple1' => '',
            'simple2' => '4040',
            'simple3' => '4040',
            'simple4' => '4040,4050'
        ];
    }
}
