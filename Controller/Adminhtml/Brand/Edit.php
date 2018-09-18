<?php

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Edit extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $pageFactory;

    protected $resultPage = false;

    protected $brands;

    /**
     * @var \MageSuite\BrandManagement\Model\BrandsRepository
     */
    protected $brandRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Model\Brands $brands,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Framework\Registry $registry
    )
    {
        $this->pageFactory = $pageFactory;
        $this->brands = $brands;
        $this->brandRepository = $brandsRepository;
        $this->registry = $registry;

        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        
        $brand = $this->getCurrentBrand($params);
        $this->registry->register('brand', $brand);

        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('MageSuite_BrandManagement::brand_menu');
        $isNew = (isset($params['id'])) ? false : true;


        if ($isNew) {
            $resultPage->getConfig()->getTitle()->prepend((__('New Brand')));
        } else {
            $resultPage->getConfig()->getTitle()->prepend((__('Edit Brand')));
        }

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

    protected function getCurrentBrand($params) {
        $id = $params['id'] ?? 0;

        if(!is_numeric($id) or $id <= 0) {
            return null;
        }

        if(isset($params['store'])){
            $storeId = $params['store'];
        } else {
            $storeId = 0;
        }

        return $this->brandRepository->getById($id, $storeId);
    }

}