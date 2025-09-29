<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Controller\Adminhtml\Brand;

/**
 * @magentoAppArea adminhtml
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\Magento\Framework\Filesystem $filesystem = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->_objectManager->create(\Magento\Framework\Filesystem::class);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture moveBrandImageToTmp
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function testUploadActionWithCorrectData(): void
    {
        $_FILES = [ // phpcs:ignore
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__ . '/../../../_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864,
            ],
        ];

        $this->getRequest()->setPostValue(['param_name' => 'brand_icon']);
        $this->dispatch('backend/brands/brand/newImage');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertTrue(isset($response['name']));
        $this->assertFalse($response['error']);

        $mediaDir = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $path = $mediaDir->getAbsolutePath() . \MageSuite\BrandManagement\Controller\Adminhtml\Brand\NewImage::BRANDS_MEDIA_PATH . DIRECTORY_SEPARATOR . $response['name'];
        $fileExist = file_exists($path);
        $this->assertTrue($fileExist);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture moveBrandImageToTmp
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function testUploadActionWithWrongData(): void
    {
        $_FILES = [ // phpcs:ignore
            'brand_icon' => [
                'param_name' => 'brand_icon',
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__ . '/../../../d/_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864,
            ],
        ];

        $this->getRequest()->setPostValue(['param_name' => 'brand_icon']);
        $this->dispatch('backend/brands/brand/newImage');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertNotEmpty($response['error']);
    }

    public static function moveBrandImageToTmp(): void
    {
        include __DIR__ . '/../../../_files/brand_image.php';
    }
}
