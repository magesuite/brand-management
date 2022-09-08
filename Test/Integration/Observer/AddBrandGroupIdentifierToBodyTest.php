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

        $domDocument = new \DOMDocument();

        $body = $this->getResponse()->getBody();
        @$domDocument->loadHTML($body); //phpcs:ignore

        $domXpath = new \DOMXPath($domDocument);
        $bodyClasses = $domXpath->query('//html/body/@class')->item(0)->textContent;

        $brandGroupIdentifier = 'special-identifier';
        $this->assertStringContainsString($brandGroupIdentifier, $bodyClasses);
    }
}
