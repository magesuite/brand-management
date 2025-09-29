<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    public const DEFAULT_EXPANDED_FACETS_COUNT_CONFIG_XML_PATH = 'smile_elasticsuite_catalogsearch_settings/catalogsearch/expanded_facets';

    public function __construct(
        protected \Magento\Framework\ObjectManagerInterface $objectManager,
        protected \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        array $data
    ) {
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
    }

    /**
     * Check if we can show this block.
     * According to @return bool
     * @see \Magento\LayeredNavigationStaging\Block\Navigation::canShowBlock
     * We should not show the block if staging is enabled and if we are currently previewing the results.
     *
     */
    public function canShowBlock(): bool
    {
        if ($this->moduleManager->isEnabled('Magento_Staging')) {
            try {
                $versionManager = $this->objectManager->get('\Magento\Staging\Model\VersionManager'); // phpcs:ignore

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
    public function getActiveFilters(): string
    {
        $requestParams = array_keys($this->getRequest()->getParams());
        $displayedFilters = $this->getDisplayedFilters();
        $expandedFacets = $this->_scopeConfig->getValue(self::DEFAULT_EXPANDED_FACETS_COUNT_CONFIG_XML_PATH);
        $activeFilters = range(0, min(count($displayedFilters), $expandedFacets) - 1);

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
    public function getDisplayedFilters(): array
    {
        $displayedFilters = array_filter(
            $this->getFilters(),
            function ($filter) {
                if ($filter->getRequestVar() == \MageSuite\BrandManagement\Model\Brands::BRAND_ATTRIBUTE_CODE) {
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
    protected function _prepareLayout() // phpcs:ignore
    {
        parent::_prepareLayout();

        return $this;
    }

    public function getClearUrl() // phpcs:ignore
    {
        return null;
    }

    public function isInline(): bool
    {
        return false;
    }
}
