<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Edit extends \Magento\Backend\App\Action
{
    public function __construct(
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page
    {
        $params = $this->getRequest()->getParams();

        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');

        $pageTitle = isset($params['entity_id']) ? __('Edit Brand') : __('New Brand');
        $resultPage->getConfig()->getTitle()->prepend((__($pageTitle)));

        return $resultPage;
    }
}
