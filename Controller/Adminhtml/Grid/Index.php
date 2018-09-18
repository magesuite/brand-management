<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Grid;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var PageFactory */
    protected $pageFactory;

    protected $resultPage = false;

    protected $brands;

    protected $resourceFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Model\Brands $brands
    )
    {
        $this->pageFactory = $pageFactory;
        $this->brands = $brands;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');
        $resultPage->getConfig()->getTitle()->prepend((__('Brands')));

        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }

    public function getResultPage()
    {
        if (!$this->resultPage) {
            $this->resultPage = $this->pageFactory->create();
        }
        return $this->resultPage;
    }

}