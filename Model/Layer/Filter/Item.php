<?php

namespace MageSuite\BrandManagement\Model\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
        parent::__construct($url, $htmlPagerBlock);
    }

    public function getUrl()
    {
        $qsParams = $this->getApplyQueryStringParams();

        $url = $this->rewriteBaseUrl($qsParams);

        if ($url === null) {
            $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $qsParams]);
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

    private function getApplyValue()
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

    private function getApplyQueryStringParams()
    {
        $qsParams = [
            $this->getFilter()->getRequestVar()      => $this->getApplyValue(),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        return $qsParams;
    }

    private function rewriteBaseUrl($qsParams)
    {
        $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);

        if ($url) {
            $baseUrlParts = explode('?', $url);
            $qsParser     = new \Zend\Stdlib\Parameters();

            $qsParser->fromArray($qsParams);

            if (count($baseUrlParts) > 1) {
                $qsParser->fromString($baseUrlParts[1]);
                $qsParams = array_merge($qsParser->toArray(), $qsParams);
                $qsParser->fromArray($qsParams);
            }

            $baseUrlParts[0] = $this->prepareBaseUrlPart($baseUrlParts[0]);

            $baseUrlParts[1] = $qsParser->toString();

            $url = isset($baseUrlParts[1]) ? implode('?', $baseUrlParts) : $baseUrlParts[0];
        }

        return urldecode($url);
    }

    public function prepareBaseUrlPart($url)
    {
        $baseUrl = $this->_url->getUrl(\MageSuite\BrandManagement\Model\Brand::BRAND_URL . '/*/*');
        $cleanUrl = str_replace($baseUrl, '', $url);
        $cleanUrlParts = explode('/', $cleanUrl);

        if ($currBrand = $this->registry->registry('current_brand')) {
            $urlPart = $currBrand->getBrandUrlKey();
        } else {
            $urlPart = $cleanUrlParts[1];
        }

        return $this->_url->getUrl(\MageSuite\BrandManagement\Model\Brand::BRAND_URL) . $urlPart;
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
