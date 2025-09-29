<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class NewImage extends \Magento\Backend\App\Action
{
    public const BRANDS_MEDIA_PATH = 'brands';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        protected \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        protected \Magento\Framework\Filesystem $filesystem,
        protected \MageSuite\BrandManagement\Model\BrandUrlProvider $brandUrlProvider,
        protected \Magento\MediaGalleryApi\Api\SaveAssetsInterface $saveAssets,
        protected \Magento\MediaGallerySynchronizationApi\Model\CreateAssetFromFileInterface $createAssetFromFile,
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\Result\Json
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $fieldId = $this->getFieldId();
            $uploader = $this->uploaderFactory->create(['fileId' => $fieldId]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $result = $uploader->save($this->getDestinationPath());

            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Unable to save uploaded file.'));
            }

            $filepath = sprintf("%s/%s", self::BRANDS_MEDIA_PATH, $result['file']);

            $asset = $this->createAssetFromFile->execute($filepath);
            $this->saveAssets->execute([$asset]);

            return $resultJson->setData([
                'name' => $result['file'],
                'full_path' => $result['file'],
                'type' => $result['type'],
                'tmp_name' => str_replace('\\', '/', $result['tmp_name']),
                'error' => false,
                'size' => $result['size'],
                'file' => $result['file'],
                'url' => $this->brandUrlProvider->get($filepath),
            ]);
        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getDestinationPath(): string
    {
        return $this->filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath(self::BRANDS_MEDIA_PATH);
    }

    public function getFieldId(): string
    {
        return $this->_request->getParam('param_name', 'image');
    }
}
