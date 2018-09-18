<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var bool
     */
    protected $resultPage = false;

    /**
     * @var \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory
     */
    protected $saveFactory;

    protected $brandParamsValidator;
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
        \MageSuite\BrandManagement\Validator\BrandParams $brandParamsValidator
    )
    {
        $this->pageFactory = $pageFactory;
        $this->saveFactory = $saveFactory;
        $this->brandParamsValidator = $brandParamsValidator;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $params = $this->_request->getParams();
        try {
            $this->brandParamsValidator->validateParams($params);

            $this->saveFactory->create()->processSave($params);
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