<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Edit extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'MageSuite_BrandManagement::create_brands';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \MageSuite\BrandManagement\Model\Brands $brands,
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        protected \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page
    {
        $params = $this->getRequest()->getParams();
        $brand = $this->getCurrentBrand($params);
        $this->registry->register('brand', $brand);
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');
        $isNew = (isset($params['id'])) ? false : true;
        $resultPage->getConfig()->getTitle()->prepend($isNew ? __('New Brand') : __('Edit Brand'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));

        return $resultPage;
    }

    protected function getCurrentBrand(array $params): ?\MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $id = $params['id'] ?? 0;

        if (!is_numeric($id) || $id <= 0) {
            return null;
        }

        $storeId = 0;

        if (isset($params['store'])) {
            $storeId = $params['store'];
        }

        return $this->brandsRepository->getById($id, $storeId);
    }
}
