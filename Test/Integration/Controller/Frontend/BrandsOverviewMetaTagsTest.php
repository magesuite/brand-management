<?php

namespace MageSuite\BrandManagement\Test\Integration\Controller\Frontend;

class BrandsOverviewMetaTagsTest extends \Magento\TestFramework\TestCase\AbstractController
{

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store brand_management/brands_overview_page_seo/meta_title example_title
     */
    public function testTitleTagOnBrandsOverviewPage()
    {
        $this->dispatch('/brands');
        $body = $this->getResponse()->getBody();
        $hasTitleTagFromPageConfig = strpos($body, '<title>example_title</title>') !== false;
        $this->assertTrue($hasTitleTagFromPageConfig);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store brand_management/brands_overview_page_seo/meta_title example_meta_title
     */
    public function testMetaTitleTagOnBrandsOverviewPage()
    {
        $this->dispatch('/brands');
        $body = $this->getResponse()->getBody();
        $hasMetaTitleTagFromPageConfig = strpos($body, '<meta name="title" content="example_meta_title"/>') !== false;
        $this->assertTrue($hasMetaTitleTagFromPageConfig);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store brand_management/brands_overview_page_seo/meta_description example_meta_description
     */
    public function testMetaDescriptionTagOnBrandsOverviewPage()
    {
        $this->dispatch('/brands');
        $body = $this->getResponse()->getBody();
        $hasDescriptionTagFromPageConfig = strpos($body, '<meta name="description" content="example_meta_description"/>') !== false;
        $this->assertTrue($hasDescriptionTagFromPageConfig);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store brand_management/brands_overview_page_seo/meta_robots NOINDEX,NOFOLLOW
     */
    public function testMetaRobotsTagOnBrandsOverviewPage()
    {
        $this->dispatch('/brands');
        $body = $this->getResponse()->getBody();
        $hasMetaRobotsTagFromPageConfig = strpos($body, '<meta name="robots" content="NOINDEX,NOFOLLOW"/>') !== false;
        $this->assertTrue($hasMetaRobotsTagFromPageConfig);
    }

}
