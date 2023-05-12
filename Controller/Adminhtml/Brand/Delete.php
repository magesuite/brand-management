<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Delete extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $pageFactory;

    /**
     * @var bool
     */
    protected $resultPage = false;

    /** @var \MageSuite\BrandManagement\Model\BrandsFactory */
    protected $brandsFactory;

    /**
     * @var \MageSuite\BrandManagement\Model\ResourceModel\BrandsFactory
     */
    protected $resourceBrandsFactory;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultRedirect;

    /**
     * Delete constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory
     * @param \MageSuite\BrandManagement\Model\ResourceModel\BrandsFactory $resourceBrandsFactory
     * @param \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\Controller\ResultFactory $resultRedirect
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\BrandsFactory $resourceBrandsFactory,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\Controller\ResultFactory $resultRedirect
    )
    {
        $this->pageFactory = $pageFactory;
        $this->brandsFactory = $brandsFactory;
        $this->resourceBrandsFactory = $resourceBrandsFactory;
        $this->brandsRepository = $brandsRepository;
        $this->eavConfig = $eavConfig;
        $this->resultRedirect = $resultRedirect;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
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

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
