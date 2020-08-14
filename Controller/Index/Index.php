<?php

namespace MageSuite\BrandManagement\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var  \MageSuite\BrandManagement\Helper\Brand
     */
    protected $brandHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    const CURRENT_BRAND = 'current_brand';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Page\Config $pageConfig
    ) {
        $this->pageFactory = $pageFactory;
        $this->brandHelper = $brandHelper;
        $this->registry = $registry;
        $this->pageConfig = $pageConfig;
        parent::__construct($context);

    }

    public function execute()
    {
        $request = $this->getRequest();
        $brandAttribute = \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE;
        $requestParam = rtrim($request->getParam($brandAttribute), '/');

        /** @var \MageSuite\BrandManagement\Model\Brands $brand */
        $brand = $this->brandHelper->getBrandsInfo($requestParam);

        if (empty($brand) || $brand->getEnabled() == 0) {
            $this->_redirect('noroute');
            return;

        }

        $request->setParams(array_merge(
            $request->getParams(),
            [
                'brand' => $brand->getBrandName()
            ]
        ));

        $this->registry->register(self::CURRENT_BRAND, $brand);

        $result = $this->pageFactory->create();


        if (!empty($brand->getLayoutUpdateXml())) {
            $result->getLayout()->getUpdate()->addUpdate($brand->getLayoutUpdateXml());
            $result->addPageLayoutHandles(['id' => $brand->getEntityId()]);
        }

        if (!empty($brand->getMetaRobots())) {
            $this->pageConfig->setRobots($brand->getMetaRobots());
        }

        if (!empty($brand->getMetaTitle())) {
            $this->pageConfig->setMetaTitle($brand->getMetaTitle());
        }

        if (!empty($brand->getMetaDescription())) {
            $this->pageConfig->setDescription($brand->getMetaDescription());
        }

        $this->_eventManager->dispatch('brand_controller_index_index', ['brand' => $brand]);

        return $result;
    }
}
