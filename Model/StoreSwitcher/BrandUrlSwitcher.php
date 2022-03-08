<?php

namespace MageSuite\BrandManagement\Model\StoreSwitcher;

class BrandUrlSwitcher implements \Magento\Store\Model\StoreSwitcherInterface
{
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RequestFactory
     */
    protected $requestFactory;

    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\HTTP\PhpEnvironment\RequestFactory $requestFactory,
        \MageSuite\BrandManagement\Helper\Configuration $configuration
    ) {
        $this->requestFactory = $requestFactory;
        $this->configuration = $configuration;
    }

    public function switch(\Magento\Store\Api\Data\StoreInterface $fromStore, \Magento\Store\Api\Data\StoreInterface $targetStore, string $redirectUrl): string
    {
        $targetUrl = $redirectUrl;
        /** @var \Magento\Framework\HTTP\PhpEnvironment\Request $request */
        $request = $this->requestFactory->create(['uri' => $targetUrl]);
        $urlPath = ltrim($request->getPathInfo(), '/');

        if ($targetStore->isUseStoreInUrl()) {
            $storeCode = preg_quote($targetStore->getCode() . '/', '/');
            $pattern = "@^($storeCode)@";
            $urlPath = preg_replace($pattern, '', $urlPath);
        }

        $fromStoreBrandRoute = $this->configuration->getRouteToBrand($fromStore->getId());
        $targetStoreBrandRoute = $this->configuration->getRouteToBrand($targetStore->getId());
        if ($fromStoreBrandRoute == $targetStoreBrandRoute) {
            return $targetUrl;
        }

        if (substr($urlPath, 0, strlen($fromStoreBrandRoute)) !== $fromStoreBrandRoute) {
            return $targetUrl;
        }

        $urlPath = preg_replace("/^$fromStoreBrandRoute/", $targetStoreBrandRoute, $urlPath);

        return $targetStore->getBaseUrl() . $urlPath;
    }
}
