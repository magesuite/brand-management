<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Save extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory $saveFactory,
        protected \MageSuite\BrandManagement\Validator\BrandParams $brandParamsValidator,
        protected \Magento\Framework\DataObjectFactory $dataObjectFactory,
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\Result\Redirect
    {
        $params = $this->_request->getParams();

        try {
            $params['is_api'] = false;
            $this->brandParamsValidator->validateParams($params);

            $paramsObject = $this->dataObjectFactory->create();
            $paramsObject->setData($params);
            $savedBrand = $this->saveFactory->create()->processSave($paramsObject);
            $this->messageManager->addSuccessMessage('Brand has been saved');
            $params['id'] = $savedBrand->getId();

            if (!empty($params['store_id'])) {
                $params['store'] = $params['store_id'];
            }

            $url = $this->_url->getUrl('brands/brand/edit', $params);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $url = $this->_url->getUrl('brands/brand/newbrand');
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($url);

        return $resultRedirect;
    }

    protected function _isAllowed(): bool
    {
        return true;
    }
}
