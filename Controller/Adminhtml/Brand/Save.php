<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var bool
     */
    protected $resultPage = false;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;
    /**
     * @var \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory
     */
    protected $saveFactory;
    /**
     * @var \MageSuite\BrandManagement\Validator\BrandParams
     */
    protected $brandParamsValidator;
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;
    
    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory $saveFactory
     * @param \MageSuite\BrandManagement\Validator\BrandParams $brandParamsValidator
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory $saveFactory,
        \MageSuite\BrandManagement\Validator\BrandParams $brandParamsValidator,
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->saveFactory = $saveFactory;
        $this->brandParamsValidator = $brandParamsValidator;
        parent::__construct($context);
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $params = $this->_request->getParams();
        try {
            $params['is_api'] = false;
            $this->brandParamsValidator->validateParams($params);

            $paramsObject = $this->dataObjectFactory->create();
            $paramsObject->setData($params);
            $this->saveFactory->create()->processSave($paramsObject);
            $this->messageManager->addSuccessMessage('Brand has been saved');
        } catch (\Exception $e)
        {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $storeId = isset($params['store_id']) ? $params['store_id'] : 0;
        $url = $this->_url->getUrl('brands/brand/edit', ['id' => $params['entity_id'], 'store' => $storeId]);
        $resultRedirect->setPath($url);

        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
