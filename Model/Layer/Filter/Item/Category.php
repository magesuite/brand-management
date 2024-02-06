<?php

namespace MageSuite\BrandManagement\Model\Layer\Filter\Item;

class Category extends \MageSuite\BrandManagement\Model\Layer\Filter\Item
{
    public function getUrl()
    {
        $catRequestVar  = $this->getFilter()->getRequestVar();
        $pageRequestVar = $this->_htmlPagerBlock->getPageVarName();
        $queryParams = [
            $catRequestVar  => $this->getValue(),
            $pageRequestVar => null,
        ];

        foreach ($this->getFilter()->getLayer()->getState()->getFilters() as $currentFilterItem) {
            $currentRequestVar = $currentFilterItem->getFilter()->getRequestVar();

            if ($currentRequestVar != $catRequestVar) {
                unset($queryParams[$currentRequestVar]);
            }
        }

        $url = $this->_url->getUrl(
            '*/*/*',
            ['_current' => true, '_use_rewrite' => true, '_query' => $queryParams]
        );

        if ($this->getUrlRewrite()) {
            $url = $this->getUrlRewrite();
        }

        $url = $this->rewriteBaseUrl($url, $queryParams);

        return $url;
    }

    protected function rewriteBaseUrl($url, $queryParams)
    {
        $baseUrlParts = explode('?', $url);
        $baseUrlParts[0] = $this->prepareBaseUrlPart($baseUrlParts[0]);
        $qsParser = new \Laminas\Stdlib\Parameters();
        $qsParser->fromArray($queryParams);

        if (count($baseUrlParts) > 1) {
            $baseUrlParts[1] = $qsParser->toString();
        }

        $url = $baseUrlParts[1] ? implode('?', $baseUrlParts) : $baseUrlParts[0];

        return urldecode($url);
    }

    public function prepareBaseUrlPart($url)
    {
        $routeToBrand = $this->configuration->getRouteToBrand();
        $baseUrl = $this->_url->getUrl($routeToBrand .'/*/*');
        $cleanUrl = str_replace($baseUrl, '', $url);
        $cleanUrlParts = explode('/', $cleanUrl);
        $urlPart = $cleanUrlParts[1];

        if ($brand = $this->registry->registry('current_brand')) {
            $urlPart = $brand->getBrandUrlKey();
        }

        return $this->_url->getUrl($routeToBrand) . $urlPart;
    }
}
