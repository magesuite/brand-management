<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\StoreSwitcher;

class BrandUrlSwitcher implements \Magento\Store\Model\StoreSwitcherInterface
{
    public function __construct(
        protected \Magento\Framework\HTTP\PhpEnvironment\RequestFactory $requestFactory,
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration
    ) {}

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

        $fromStoreBrandRoute = $this->configuration->getRouteToBrand((int)$fromStore->getId());
        $targetStoreBrandRoute = $this->configuration->getRouteToBrand((int)$targetStore->getId());

        if ($fromStoreBrandRoute == $targetStoreBrandRoute) {
            return $targetUrl;
        }

        if (!str_starts_with($urlPath, $fromStoreBrandRoute)) {
            return $targetUrl;
        }

        $urlPath = preg_replace("/^$fromStoreBrandRoute/", $targetStoreBrandRoute, $urlPath);

        return $targetStore->getBaseUrl() . $urlPath;
    }
}
