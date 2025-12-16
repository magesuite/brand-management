<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Save extends \Magento\Backend\App\Action
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
            $url = $this->_url->getUrl('brands/brand/edit', ['id' => $savedBrand->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            if (!empty($params['entity_id'])) {
                $url = $this->_url->getUrl('brands/brand/edit', ['id' => $params['entity_id']]);
            } else {
                $url = $this->_url->getUrl('brands/brand/newbrand');
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($url);

        return $resultRedirect;
    }
}
