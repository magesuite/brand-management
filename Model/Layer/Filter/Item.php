<?php

namespace MageSuite\BrandManagement\Model\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    protected \Magento\Framework\Registry $registry;

    protected \MageSuite\BrandManagement\Helper\Configuration $configuration;

    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\Registry $registry,
        \MageSuite\BrandManagement\Helper\Configuration $configuration
    ) {
        $this->registry = $registry;
        $this->configuration = $configuration;
        parent::__construct($url, $htmlPagerBlock);
    }

    public function getUrl()
    {
        $url = $this->_url->getUrl('*/*/*', [
            '_current' => true,
            '_use_rewrite' => true
        ]);
        $qsParams = $this->getApplyQueryStringParams();
        $url = $this->rewriteBaseUrl($url, $qsParams);

        if ($url === null) {
            $url = $this->_url->getUrl('*/*/*', [
                '_current' => true,
                '_use_rewrite' => true,
                '_query' => $qsParams
            ]);
        }

        return $url;
    }

    public function toArray(array $keys = [])
    {
        $data = parent::toArray($keys);

        if (in_array('url', $keys) || empty($keys)) {
            $data['url'] = $this->getUrl();
        }

        if (in_array('is_selected', $keys) || empty($keys)) {
            $data['is_selected'] = (bool) $this->getIsSelected();
        }

        return $data;
    }

    protected function getApplyValue()
    {
        $value = $this->getValue();

        if (is_array($this->getApplyFilterValue())) {
            $value = $this->getApplyFilterValue();
        }

        if (is_array($value) && count($value) == 1) {
            $value = current($value);
        }

        return $value;
    }

    protected function getApplyQueryStringParams()
    {
        $qsParams = [
            $this->getFilter()->getRequestVar() => $this->getApplyValue(),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        return $qsParams;
    }

    protected function rewriteBaseUrl($url, $queryParams)
    {
        $baseUrlParts = explode('?', $url);
        $qsParser = new \Zend\Stdlib\Parameters();
        $qsParser->fromArray($queryParams);

        if (count($baseUrlParts) > 1) {
            $qsParser->fromString($baseUrlParts[1]);
            $qsParams = array_merge($qsParser->toArray(), $queryParams);
            $qsParser->fromArray($queryParams);
        }

        $baseUrlParts[0] = $this->prepareBaseUrlPart($baseUrlParts[0]);
        $baseUrlParts[1] = $qsParser->toString();
        $url = isset($baseUrlParts[1]) ? implode('?', $baseUrlParts) : $baseUrlParts[0];

        return $url;
    }

    public function prepareBaseUrlPart($url)
    {
        $routeToBrand = $this->configuration->getRouteToBrand();
        $baseUrl = $this->_url->getUrl($routeToBrand . '/*/*');
        $cleanUrl = str_replace($baseUrl, '', $url);
        $cleanUrlParts = explode('/', $cleanUrl);
        $urlPart = $cleanUrlParts[1];

        if ($brand = $this->registry->registry('current_brand')) {
            $urlPart = $brand->getBrandUrlKey();
        }

        return $this->_url->getUrl($routeToBrand) . $urlPart;
    }

    public function getRemoveUrl()
    {
        $urlParams = [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [$this->getFilter()->getRequestVar() => null],
            '_escape' => true,
        ];
        $url = $this->_url->getUrl('*/*/*', $urlParams);
        $baseUrlParts = explode('?', $url);
        $baseUrlParts[0] = $this->prepareBaseUrlPart($baseUrlParts[0]);
        $url = isset($baseUrlParts[1]) ? implode('?', $baseUrlParts) : $baseUrlParts[0];

        return $url;
    }
}
