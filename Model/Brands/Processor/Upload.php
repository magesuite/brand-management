<?php
namespace MageSuite\BrandManagement\Model\Brands\Processor;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Api\ImageContentValidatorInterface;
use Magento\Framework\Api\Uploader;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Psr\Log\LoggerInterface;

class Upload
{
    protected $mimeTypeExtensionMap = [
        'image/jpg'     => 'jpg',
        'image/jpeg'    => 'jpeg',
        'image/gif'     => 'gif',
        'image/png'     => 'png',
        'image/svg+xml' => 'svg'
    ];

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * Media Directory object (writable)
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * System Tmp Directory object (writable)
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $sysTmpDirectory;

    /**
     * @var \Magento\Framework\Api\ImageContentValidatorInterface
     */
    protected $imageContentValidator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Api\Uploader
     */
    protected $uploader;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $dbFileStorage;

    const FILE_DIR = 'brands';

    const MAX_FILE_SIZE = 1024;

    /**
     * @param Filesystem $filesystem
     * @param ImageContentValidatorInterface $imageContentValidator
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param Uploader $uploader
     * @param UploaderFactory $uploaderFactory
     * @param Database $dbFileStorage
     */
    public function __construct(
        Filesystem $filesystem,
        ImageContentValidatorInterface $imageContentValidator,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Uploader $uploader,
        UploaderFactory $uploaderFactory,
        Database $dbFileStorage
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->sysTmpDirectory = $filesystem->getDirectoryWrite(DirectoryList::SYS_TMP);
        $this->filesystem = $filesystem;
        $this->imageContentValidator = $imageContentValidator;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->uploader = $uploader;
        $this->uploaderFactory = $uploaderFactory;
        $this->dbFileStorage = $dbFileStorage;
    }

    /**
     * Save image
     *
     * @param array|null $imageData
     * @return array
     * @throws LocalizedException
     */
    public function processUpload($imageData = null)
    {
        try {
            if ($imageData) {
                // Process file from api
                $fileAttributes = $this->prepareUploadBase64Encoded($imageData['imageData']);
                $fileId = $imageData['type'];
                $uploader = $this->uploader->processFileAttributes($fileAttributes);
            } else {
                // Process file from upload
                $fileId = key($_FILES);
                $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
            }

            $path = $this->getPathForField($fileId);
            $basePath = self::FILE_DIR . '/' . $path;
            $tmpBasePath = 'tmp/' . $basePath;

            $destination = $this->getAbsoluteMediaPath($tmpBasePath);

            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $uploader->addValidateCallback('size', $this, 'validateMaxSize');

            if ($imageData) {
                $result = $uploader->save($destination, $fileAttributes['name']);
            } else {
                $result = $uploader->save($destination);
            }
            if (!$result) {
                throw new LocalizedException(__('File can not be saved to the destination folder.'));
            }

            $result['tmp_name'] = $this->prepareFile($result['tmp_name']);
            $result['path'] = $this->prepareFile($result['path']);
            $result['url'] = $this->getTmpMediaUrl($result['file'], $tmpBasePath);
            $result['name'] = pathinfo($result['file'], PATHINFO_BASENAME);

            if (isset($result['file'])) {
                $relativePath = $this->getFilePath($tmpBasePath, $result['file']);
                $this->dbFileStorage->saveFile($relativePath);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $result;
    }

    /**
     * Move image from tmp directory
     */
    public function moveFileFromTmp($imageName, $type)
    {
        $path = $this->getPathForField($type);
        $basePath = self::FILE_DIR . '/' . $path;
        $tmpBasePath = 'tmp/' . $basePath;

        $imageBasePath = $this->getFilePath($basePath, $imageName);
        $imageTmpBasePath = $this->getFilePath($tmpBasePath, $imageName);

        try {
            $this->dbFileStorage->copyFile(
                $imageTmpBasePath,
                $imageBasePath
            );
            $this->mediaDirectory->renameFile(
                $imageTmpBasePath,
                $imageBasePath
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(__('Something went wrong while saving the file(s).'));
        }

        return $imageName;
    }

    /**
     * Generate uploaded file from base 64 encoded image
     *
     * @param $imageData
     * @return array
     */
    public function prepareUploadBase64Encoded($imageData)
    {
        if (!$this->imageContentValidator->isValid($imageData)) {
            throw new InputException(new \Magento\Framework\Phrase('The image content is invalid. Verify the content and try again.'));
        }

        $fileContent = @base64_decode($imageData->getBase64EncodedData(), true);
        $fileName = $this->getFileName($imageData);
        $tmpFileName = substr(md5(rand()), 0, 7) . '.' . $fileName;
        $this->sysTmpDirectory->writeFile($tmpFileName, $fileContent);
        $absTmpFileName = $this->sysTmpDirectory->getAbsolutePath() . $tmpFileName;

        return [
            'tmp_name' => $absTmpFileName,
            'name' => $fileName,
            'type' => $imageData->getType(),
            'size' => filesize($absTmpFileName)
        ];
    }

    /**
     * Get filename with extension from image data
     *
     * @param array $imageContent
     * @return string
     * @throws InputException
     */
    protected function getFileName($imageContent)
    {
        $fileName = $imageContent->getName();
        if (!pathinfo($fileName, PATHINFO_EXTENSION)) {
            if (!$imageContent->getType() || !$this->getMimeTypeExtension($imageContent->getType())) {
                throw new InputException(new \Magento\Framework\Phrase('Cannot recognize image extension.'));
            }
            $fileName .= '.' . $this->getMimeTypeExtension($imageContent->getType());
        }
        return $fileName;
    }

    protected function getFilePath($path, $file)
    {
        return rtrim($path, '/') . '/' . ltrim($file, '/');
    }

    /**
     * Get extension by mimeType
     *
     * @param string $mimeType
     * @return string
     */
    protected function getMimeTypeExtension($mimeType)
    {
        return $this->mimeTypeExtensionMap[$mimeType] ?? '';
    }

    /**
     * Retrieve absolute temp media path
     *
     * @param string $path
     * @return string
     */
    protected function getAbsoluteMediaPath($path)
    {
        return $this->mediaDirectory->getAbsolutePath($path);
    }

    /**
     * @return array
     */
    protected function getAllowedExtensions()
    {
        return array_values($this->mimeTypeExtensionMap);
    }

    /**
     * @param string $fileId
     * @return string
     */
    protected function getPathForField($type)
    {
        return str_replace('brand_', '', $type);
    }

    /**
     * Validation callback for checking max file size
     *
     * @param  string $filePath Path to temporary uploaded file
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateMaxSize($filePath)
    {
        $directory = $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        if ($directory->stat($directory->getRelativePath($filePath))['size'] > self::MAX_FILE_SIZE * 1024) {
            throw new LocalizedException(__(
                'The file you\'re uploading exceeds the server size limit of %1 kilobytes.',
                self::MAX_FILE_SIZE
            ));
        }
    }

    /**
     * Retrieve temp media url
     *
     * @param string $file
     * @param string $path
     * @return string
     */
    protected function getTmpMediaUrl($file, $path)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . $this->getFilePath($path, $this->prepareFile($file));
    }

    /**
     * Prepare file
     *
     * @param string $file
     * @return string
     */
    protected function prepareFile($file)
    {
        return str_replace('\\', '/', $file);
    }
}
