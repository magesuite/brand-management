<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Controller;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AllTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItReturnsBrandListPage(): void
    {
        $this->dispatch('brands/');

        $this->assertEquals('brands_index_all', $this->getRequest()->getFullActionName());
    }
}
