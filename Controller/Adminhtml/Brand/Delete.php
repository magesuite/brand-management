<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Delete extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'MageSuite_BrandManagement::delete_brands';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        protected \MageSuite\BrandManagement\Model\ResourceModel\BrandsFactory $resourceBrandsFactory,
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        protected \Magento\Eav\Model\Config $eavConfig,
        protected \Magento\Framework\Controller\ResultFactory $resultRedirect
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        try {
            $params = $this->_request->getParams();
            $brand = $this->brandsRepository->getById($params['id']);
            $this->brandsRepository->delete($brand);
            $this->messageManager->addSuccessMessage('Brand has been deleted');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $url = $this->_url->getUrl('brands/grid/index');
        $resultRedirect->setPath($url);

        return $resultRedirect;
    }
}
