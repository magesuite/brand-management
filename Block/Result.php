<?php

namespace MageSuite\BrandManagement\Block;

class Result extends \Magento\CatalogSearch\Block\Result
{
    /**
     * @var  \MageSuite\BrandManagement\Helper\Brand
     */
    protected $brandHelper;

    protected $registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\CatalogSearch\Helper\Data $catalogSearchData,
        \Magento\Search\Model\QueryFactory $queryFactory,
        \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->brandHelper = $brandHelper;
        $this->registry = $registry;
        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);
    }

    protected function _prepareLayout()
    {
        $result = parent::_prepareLayout();

        $brand = $this->registry->registry('current_brand');
        $title = __('Brand') . ': ' . $brand->getBrandName();
        $this->pageConfig->getTitle()->set($title);

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            )->addCrumb(
                'search',
                ['label' => $title, 'title' => $title]
            );
        }

        return $result;
    }

    public function getNoResultText() {
        return __('Your search returned no results.');
    }
}
