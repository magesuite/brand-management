<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Newbrand extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultForwardFactory;

    protected $resultPage = false;

    protected $brands;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }

    protected function _isAllowed()
    {
        return true;
    }
}