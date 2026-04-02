<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Grid;

class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'MageSuite_BrandManagement::list_brands';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');
        $resultPage->getConfig()->getTitle()->prepend((__('Brands')));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));

        return $resultPage;
    }
}
