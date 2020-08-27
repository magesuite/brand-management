<?php

namespace MageSuite\BrandManagement\Test\Integration\Controller\Adminhtml\Brand;

/**
 * @magentoAppArea adminhtml
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->_objectManager->create('Magento\Framework\Filesystem');
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture moveBrandImageToTmp
     */
    public function testUploadActionWithCorrectData()
    {
        $_FILES = [
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];

        $this->dispatch('backend/brands/brand/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertTrue(isset($response['name']));
        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath() . 'brands/' . $response['name'];
        $fileExist = file_exists($path);
        $this->assertTrue($fileExist);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture moveBrandImageToTmp
     */
    public function testUploadActionWithWrongData()
    {
        $_FILES = [
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../d/_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];

        $this->dispatch('backend/brands/brand/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertFalse($response);
    }

    public static function moveBrandImageToTmp() {
        include __DIR__.'/../../../_files/brand_image.php';
    }
}

