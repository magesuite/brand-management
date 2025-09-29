<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Brands\Processor;

class Upload
{
    public function __construct(
        protected \Magento\Framework\Filesystem $filesystem,
        protected \Magento\Framework\Api\ImageContentValidatorInterface $imageContentValidator,
        protected \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
    ) {}

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Random\RandomException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function processUpload(\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $imageData): array
    {
        $fileAttributes = $this->prepareUploadBase64Encoded($imageData);

        $uploader = $this->uploaderFactory->create(['fileId' => $fileAttributes]);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);

        $destinationFolder = $this->filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath(\MageSuite\BrandManagement\Controller\Adminhtml\Brand\NewImage::BRANDS_MEDIA_PATH);

        $result = $uploader->save($destinationFolder);

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Image save failed.'));
        }

        $result['image'] = \MageSuite\BrandManagement\Controller\Adminhtml\Brand\NewImage::BRANDS_MEDIA_PATH . DIRECTORY_SEPARATOR . $result['file'];

        return $result;
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException|\Magento\Framework\Exception\InputException|\Random\RandomException
     * @SuppressWarnings(PHPMD.ErrorControlOperator)
     */
    public function prepareUploadBase64Encoded(\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $imageData): array
    {
        if (!$this->imageContentValidator->isValid($imageData)) {
            throw new \Magento\Framework\Exception\InputException(
                new \Magento\Framework\Phrase('The image content is invalid. Verify the content and try again.')
            );
        }

        $fileContent = @base64_decode($imageData->getBase64EncodedData(), true); // phpcs:ignore
        $tmpDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::SYS_TMP);
        $fileName = $imageData->getName();
        $tmpFileName = bin2hex(random_bytes(4));
        $tmpDirectory->writeFile($tmpFileName, $fileContent);

        return [
            'tmp_name' => $tmpDirectory->getAbsolutePath() . $tmpFileName,
            'name' => $fileName,
        ];
    }
}
