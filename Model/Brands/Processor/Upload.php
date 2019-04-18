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
    protected $imageContentValidator;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \MageSuite\BrandManagement\Model\BrandsFactory
     */
    protected $brand;
    /**
     * @var \Magento\Framework\Api\Uploader
     */
    protected $uploader;

    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\BrandManagement\Model\BrandsFactory $brand,
        \Magento\Framework\Api\ImageContentValidatorInterface $imageContentValidator,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Api\Uploader $uploader
    )
    {
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->brand = $brand;
        $this->imageContentValidator = $imageContentValidator;
        $this->logger = $logger;
        $this->uploader = $uploader;
    }

    public function processUpload($imageData = null)
    {
        if($imageData) {
            $fileAttributes = $this->prepareUploadBase64Encoded($imageData);
        } else {
            $fileAttributes = $this->prepareUploadImage();
        }

        try {
            $this->uploader->processFileAttributes($fileAttributes);
            $this->uploader->setFilesDispersion(false);
            $this->uploader->setFilenamesCaseSensitivity(false);
            $this->uploader->setAllowRenameFiles(true);
            $destinationFolder = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                ->getAbsolutePath('brands/');
            $result = $this->uploader->save($destinationFolder, $fileAttributes['name']);

            if($imageData){
                return $this->uploader->getUploadedFileName();
            } else {
                $imagePath = $this->uploader->getUploadedFileName();

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
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return false;
    }

    public function prepareUploadImage()
    {
        if(!isset($_FILES) && !$_FILES['brand_icon']['name']) {
            $result = ['error' => __('Image file has been not uploaded'), 'errorcode' => __('Image file has been not uploaded')];
            return $result;
        }

        return [
            'tmp_name' => $_FILES['brand_icon']['tmp_name'],
            'name' => $_FILES['brand_icon']['name']
        ];
    }

    public function prepareUploadBase64Encoded($imageData)
    {
        if (!$this->imageContentValidator->isValid($imageData)) {
            throw new \Magento\Framework\Exception\InputException(new \Magento\Framework\Phrase('The image content is invalid. Verify the content and try again.'));
        }

        $fileContent = @base64_decode($imageData->getBase64EncodedData(), true);
        $tmpDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::SYS_TMP);
        $fileName = $this->getFileName($imageData);
        $tmpFileName = substr(md5(rand()), 0, 7) . '.' . $fileName;
        $tmpDirectory->writeFile($tmpFileName, $fileContent);

        return [
            'tmp_name' => $tmpDirectory->getAbsolutePath() . $tmpFileName,
            'name' => $fileName
        ];
    }

    protected function getFileName($imageContent)
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