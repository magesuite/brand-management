<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Delete extends \Magento\Backend\App\Action
{
    public const PARAM_ENTITY_ID = 'entity_id';

    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\Result\Redirect
    {
        try {
            $entityId = (int)$this->_request->getParam(self::PARAM_ENTITY_ID);
            $brand = $this->brandsRepository->getById($entityId);

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
