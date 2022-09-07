<?php

namespace MageSuite\BrandManagement\Test\Integration\Observer;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AddBrandGroupIdentifierToBodyTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brand_with_identifier.php
     */
    public function testItAddsIdentifierToBodyClass()
    {
        $this->dispatch('brands/brand_with_identifier');

        $body = $this->getResponse()->getBody();

        $expectedClass = sprintf('%s %s', \MageSuite\BrandManagement\Observer\AddBrandGroupIdentifierToBody::BRAND_PAGE_DEFAULT_BODY_CLASS, 'special-identifier');
        $this->assertStringContainsString($expectedClass, $body);
    }
}
