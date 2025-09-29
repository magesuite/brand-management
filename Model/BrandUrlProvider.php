<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

class BrandUrlProvider
{
    public function __construct(
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
    ) {}

    public function get(string $icon, ?int $storeId = null): string
    {
        $store = $this->storeManager->getStore($storeId);
        $mediaUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $mediaUrl = rtrim($mediaUrl, '/');

        return sprintf('%s/%s', $mediaUrl, $icon);
    }
}
