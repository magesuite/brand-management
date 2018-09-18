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

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        \Magento\Framework\Registry $registry
    )
    {
        $this->pageFactory = $pageFactory;
        $this->brandHelper = $brandHelper;
        $this->registry = $registry;
        parent::__construct($context);

    }

    public function execute()
    {
        $request = $this->getRequest();

        $brandAttribute = \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE;

        /** @var \MageSuite\BrandManagement\Model\Brands $brand */
        $brand = $this->brandHelper->getBrandsInfo($request->getParam($brandAttribute));

        if(empty($brand) || $brand->getEnabled() == 0){
            $this->_redirect('noroute');
            return;

        }

        $request->setParams(array_merge(
            $request->getParams(),
            [
                'brand' => $brand->getBrandName()
            ]
        ));

        $this->registry->register('current_brand', $brand);

        $result = $this->pageFactory->create();


        if (!empty($brand->getLayoutUpdateXml())) {
            $result->getLayout()->getUpdate()->addUpdate($brand->getLayoutUpdateXml());
            $result->addPageLayoutHandles(['id' => $brand->getEntityId()]);
        }

        return $result;
    }
}