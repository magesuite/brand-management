<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    public const CURRENT_BRAND = 'current_brand';

    public function __construct(
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        protected \Magento\Framework\Registry $registry,
        protected \Magento\Framework\View\Page\Config $pageConfig,
        protected \MageSuite\ContentConstructorFrontend\Service\LayoutContentUpdateService $layoutContentUpdateService,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page|\Magento\Framework\App\ResponseInterface
    {
        $request = $this->getRequest();
        $brandAttribute = \MageSuite\BrandManagement\Model\Brands::BRAND_ATTRIBUTE_CODE;
        $requestParam = $request->getParam($brandAttribute);

        if (empty($requestParam)) {
            return $this->_redirect('noroute');
        }

        /** @var \MageSuite\BrandManagement\Model\Brands $brand */
        $brand = $this->brandHelper->getBrandsInfo($requestParam);

        if (!$brand || !$brand->getEnabled()) {
            return $this->_redirect('noroute');
        }

        $request->setParams([
            'brand' => $brand->getBrandName()
        ]);

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
