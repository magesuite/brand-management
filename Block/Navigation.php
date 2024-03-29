<?php

namespace MageSuite\BrandManagement\Block;

use Magento\Catalog\Model\Layer\AvailabilityFlagInterface;
use Magento\Catalog\Model\Layer\FilterList;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Module\Manager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template\Context;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    const DEFAULT_EXPANDED_FACETS_COUNT_CONFIG_XML_PATH = 'smile_elasticsuite_catalogsearch_settings/catalogsearch/expanded_facets';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Manager
     */
    private $moduleManager;

    public function __construct(
        Context $context,
        Resolver $layerResolver,
        FilterList $filterList,
        AvailabilityFlagInterface $visibilityFlag,
        ObjectManagerInterface $objectManager,
        Manager $moduleManager,
        array $data
    ) {
        $this->objectManager = $objectManager;
        $this->moduleManager = $moduleManager;

        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
    }

    /**
     * Check if we can show this block.
     * According to @see \Magento\LayeredNavigationStaging\Block\Navigation::canShowBlock
     * We should not show the block if staging is enabled and if we are currently previewing the results.
     *
     * @return bool
     */
    public function canShowBlock()
    {
        if ($this->moduleManager->isEnabled('Magento_Staging')) {
            try {
                $versionManager = $this->objectManager->get('\Magento\Staging\Model\VersionManager');

                return parent::canShowBlock() && !$versionManager->isPreviewVersion();
            } catch (\Exception $exception) {
                return parent::canShowBlock();
            }
        }

        return parent::canShowBlock();
    }

    /**
     * Return index of the facets that are expanded for the current page :
     *
     *  - nth first facets (depending of config)
     *  - facets with at least one selected filter
     *
     * @return string
     */
    public function getActiveFilters()
    {
        $requestParams    = array_keys($this->getRequest()->getParams());
        $displayedFilters = $this->getDisplayedFilters();
        $expandedFacets   = $this->_scopeConfig->getValue(self::DEFAULT_EXPANDED_FACETS_COUNT_CONFIG_XML_PATH);
        $activeFilters    = range(0, min(count($displayedFilters), $expandedFacets) - 1);

        foreach ($displayedFilters as $index => $filter) {
            if (in_array($filter->getRequestVar(), $requestParams)) {
                $activeFilters[] = $index;
            }
        }

        return json_encode($activeFilters);
    }

    /**
     * Returns facet that are displayed.
     *
     * @return array
     */
    public function getDisplayedFilters()
    {
        $displayedFilters = array_filter(
            $this->getFilters(),
            function ($filter) {
                if($filter->getRequestVar() == \MageSuite\BrandManagement\Model\Brand::BRAND_ATTRIBUTE_CODE) {
                    return false;
                }

                return $filter->getItemsCount() > 0;
            }
        );

        return array_values($displayedFilters);
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * {@inheritDoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }

    public function getClearUrl()
    {
        return null;
    }

    public function isInline()
    {
        return false;
    }
}
