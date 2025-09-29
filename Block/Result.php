<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block;

class Result extends \Magento\CatalogSearch\Block\Result implements \Magento\Framework\DataObject\IdentityInterface
{
    public function __construct(
        protected \MageSuite\BrandManagement\Helper\Brand $brandHelper,
        protected \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\CatalogSearch\Helper\Data $catalogSearchData,
        \Magento\Search\Model\QueryFactory $queryFactory,
        array $data = []
    ) {
        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);
    }

    protected function _prepareLayout() // phpcs:ignore
    {
        $result = parent::_prepareLayout();

        $brand = $this->registry->registry('current_brand');
        $title = $this->getPageTitle($brand);
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

    public function getNoResultText(): string
    {
        return (string)__('Your search returned no results.');
    }

    public function getPageTitle(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): string
    {
        return __('Brand') . ': ' . $brand->getBrandName();
    }

    public function getIdentities(): array
    {
        $brand = $this->registry->registry('current_brand');

        return $brand->getIdentities();
    }
}
