<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Controller;

class RouterTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store brand_management/general/route_to_brand marken
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brands_integration.php
     */
    public function testBrandPageWithChangedRoute()
    {
        $this->dispatch('marken/urlkey');
        $content = $this->getResponse()->getBody();
        $this->assertStringContainsString('<meta name="title" content="Test meta title"/>', $content);
    }
}
