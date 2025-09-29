<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Grid;

class Index extends \Magento\Backend\App\Action
{
    public function __construct(
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \MageSuite\BrandManagement\Model\Brands $brands,
        \Magento\Backend\App\Action\Context $context,
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');
        $resultPage->getConfig()->getTitle()->prepend((__('Brands')));

        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));

        return $resultPage;
    }
}
