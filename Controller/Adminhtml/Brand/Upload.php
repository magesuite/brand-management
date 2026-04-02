<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Upload extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'MageSuite_BrandManagement::update_brands';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \MageSuite\BrandManagement\Model\Brands\Processor\UploadFactory $uploadProcessor
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        try {
            $result = $this->uploadProcessor->create()->processUpload();
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)->setData($result);
    }
}
