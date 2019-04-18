<?php
namespace MageSuite\BrandManagement\Model\Brands\Processor;

class Upload
{
    protected $mimeTypeExtensionMap = [
        'image/jpg' => 'jpg',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/png' => 'png',
    ];
    /**
     * @var \Magento\Framework\Api\ImageContentValidatorInterface
     */
    private $imageContentValidator;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $mediaUploaderFactory;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \MageSuite\BrandManagement\Model\BrandsFactory
     */
    private $brand;
    /**
     * @var \Magento\Framework\Api\Uploader
     */
    private $uploader;

    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $mediaUploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\BrandManagement\Model\BrandsFactory $brand,
        \Magento\Framework\Api\ImageContentValidatorInterface $imageContentValidator,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Api\Uploader $uploader
    )
    {
        $this->mediaUploaderFactory = $mediaUploaderFactory;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->brand = $brand;
        $this->imageContentValidator = $imageContentValidator;
        $this->logger = $logger;
        $this->uploader = $uploader;
    }

    public function processUpload()
    {
        if(!isset($_FILES) && !$_FILES['brand_icon']['name']) {
            $result = ['error' => __('Image file has been not uploaded'), 'errorcode' => __('Image file has been not uploaded')];
            return $result;
        }

        $imageFieldName = array_keys($_FILES);

        $mediaUploader = $this->mediaUploaderFactory->create(['fileId' => $imageFieldName[0]]);
        $mediaUploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);
        $mediaUploader->setAllowRenameFiles(false);
        $mediaUploader->setFilesDispersion(false);

        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath('brands/');

        $result = $mediaUploader->save($path);

        $imagePath = $mediaUploader->getUploadedFileName();


        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);

        $result['url'] = $this->brand->create()->getBrandIconUrl($imagePath);

        $result['name'] = $result['file'];


        return $result;
    }

    public function processUploadBase64Encoded($imageData)
    {
        if (!$this->imageContentValidator->isValid($imageData)) {
            throw new \Magento\Framework\Exception\InputException(new \Magento\Framework\Phrase('The image content is invalid. Verify the content and try again.'));
        }

        $fileContent = @base64_decode($imageData->getBase64EncodedData(), true);
        $tmpDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::SYS_TMP);
        $fileName = $this->getFileName($imageData);
        $tmpFileName = substr(md5(rand()), 0, 7) . '.' . $fileName;
        $tmpDirectory->writeFile($tmpFileName, $fileContent);

        $fileAttributes = [
            'tmp_name' => $tmpDirectory->getAbsolutePath() . $tmpFileName,
            'name' => $imageData->getName()
        ];

        try {
            $this->uploader->processFileAttributes($fileAttributes);
            $this->uploader->setFilesDispersion(false);
            $this->uploader->setFilenamesCaseSensitivity(false);
            $this->uploader->setAllowRenameFiles(true);
            $destinationFolder = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                ->getAbsolutePath('brands/');
            $this->uploader->save($destinationFolder, $fileName);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return $this->uploader->getUploadedFileName();
    }

    private function getFileName($imageContent)
    {
        $fileName = $imageContent->getName();
        if (!pathinfo($fileName, PATHINFO_EXTENSION)) {
            if (!$imageContent->getType() || !$this->getMimeTypeExtension($imageContent->getType())) {
                throw new \Magento\Framework\Exception\InputException(new \Magento\Framework\Phrase('Cannot recognize image extension.'));
            }
            $fileName .= '.' . $this->getMimeTypeExtension($imageContent->getType());
        }
        return $fileName;
    }

    protected function getMimeTypeExtension($mimeType)
    {
        return $this->mimeTypeExtensionMap[$mimeType] ?? '';
    }
}