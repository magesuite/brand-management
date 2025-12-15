<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var \MageSuite\BrandManagement\Model\Brands\Processor\UploadFactory
     */
    protected $uploadProcessor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\BrandManagement\Model\Brands\Processor\UploadFactory $uploadProcessor
    )
    {
        $this->uploadProcessor = $uploadProcessor;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultFactory
     */
    public function execute()
    {
        try {
            $result = $this->uploadProcessor->create()->processUpload();
        } catch (\Exception $e)
        {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)->setData($result);
    }
}
