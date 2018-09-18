<?php
namespace MageSuite\BrandManagement\Model\Brands\Processor;

class Upload
{
    protected $uploaderFactory;
    protected $directoryList;
    protected $filesystem;
    protected $storeManager;
    protected $brand;

    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\BrandManagement\Model\BrandsFactory $brand
    )
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->brand = $brand;
    }

    public function processUpload()
    {
        if(!isset($_FILES) && !$_FILES['brand_icon']['name']) {
            $result = ['error' => __('Image file has been not uploaded'), 'errorcode' => __('Image file has been not uploaded')];
            return $result;
        }

        $imageFieldName = array_keys($_FILES);
        $uploader = $this->uploaderFactory->create(['fileId' => $imageFieldName[0]]);

        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);

        $uploader->setAllowRenameFiles(false);

        $uploader->setFilesDispersion(false);

        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath('brands/');

        $result = $uploader->save($path);

        $imagePath = $uploader->getUploadedFileName();


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
}