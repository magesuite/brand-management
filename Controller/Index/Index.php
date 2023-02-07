<?php

namespace MageSuite\BrandManagement\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected \Magento\Framework\View\Result\PageFactory $pageFactory;

    protected \MageSuite\BrandManagement\Helper\Brand $brandHelper;

    protected \Magento\Framework\Registry $registry;

    protected \Magento\Framework\View\Page\Config $pageConfig;

    protected \MageSuite\ContentConstructorFrontend\Service\LayoutContentUpdateService $layoutContentUpdateService;

    const CURRENT_BRAND = 'current_brand';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Page\Config $pageConfig,
        \MageSuite\ContentConstructorFrontend\Service\LayoutContentUpdateService $layoutContentUpdateService
    ) {
        $this->pageFactory = $pageFactory;
        $this->brandHelper = $brandHelper;
        $this->registry = $registry;
        $this->pageConfig = $pageConfig;
        $this->layoutContentUpdateService = $layoutContentUpdateService;

        parent::__construct($context);
    }

    public function execute()
    {
        $request = $this->getRequest();
        $brandAttribute = \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE;
        $requestParam = $request->getParam($brandAttribute);

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
        $this->layoutContentUpdateService->addContentConstructorToUpdateLayout($result, $brand);

        return $result;
    }
}
