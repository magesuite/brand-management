<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Controller\Adminhtml\Brand;

/**
 * @magentoAppArea adminhtml
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\Magento\Framework\Filesystem $filesystem = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filesystem = $this->_objectManager->create('Magento\Framework\Filesystem');
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brand_image.php
     */
    public function testUploadActionWithCorrectData(): void
    {
        $_FILES = [ //phpcs:ignore
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];
        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->dispatch('backend/brands/brand/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertTrue(isset($response['name']));
        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath() . 'brands/' . $response['name'];
        $fileExist = file_exists($path);
        $this->assertTrue($fileExist);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture MageSuite_BrandManagement::Test/Integration/_files/brand_image.php
     */
    public function testUploadActionWithWrongData(): void
    {
        $_FILES = [ //phpcs:ignore
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../d/_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];
        $this->getRequest()->setMethod(\Laminas\Http\Request::METHOD_POST);
        $this->dispatch('backend/brands/brand/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertFalse($response);
    }
}
