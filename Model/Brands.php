<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

/**
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Brands extends \Magento\Framework\Model\AbstractExtensibleModel implements \MageSuite\BrandManagement\Api\Data\BrandsInterface,
                                                                                 \Magento\Framework\DataObject\IdentityInterface
{
    public const ENTITY = 'brands';
    public const CACHE_TAG = 'brand';
    public const BRAND_ATTRIBUTE_CODE = 'brand';

    protected $_eventPrefix = 'brand'; // phpcs:ignore
    protected $_eventObject = 'brand'; // phpcs:ignore
    protected $_cacheTag = self::CACHE_TAG; // phpcs:ignore

    public function __construct(
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        protected \MageSuite\BrandManagement\Model\UrlVerifier $urlVerifier,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\BrandManagement\Model\Brands\Validator $validator,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        ?\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        ?\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(\MageSuite\BrandManagement\Model\ResourceModel\Brands::class);
    }

    public function getEntityId(): ?int
    {
        return (int)$this->getData('entity_id') ?: null;
    }

    public function setEntityId($entityId): self // phpcs:ignore
    {
        return $this->setData('entity_id', $entityId);
    }

    public function getBrandName(): ?string
    {
        return $this->getData('brand_name');
    }

    public function setBrandName(?string $brandName): self
    {
        return $this->setData('brand_name', $brandName);
    }

    public function getLayoutUpdateXml(): ?string
    {
        return $this->getData('layout_update_xml');
    }

    public function setLayoutUpdateXml(?string $xml): self
    {
        return $this->setData('layout_update_xml', $xml);
    }

    public function getContentConstructorContent(): ?string
    {
        return $this->getData('content_constructor_content');
    }

    public function setContentConstructorContent(?string $json): self
    {
        return $this->setData('content_constructor_content', $json);
    }

    public function getBrandIcon(): ?string
    {
        return $this->getData('brand_icon');
    }

    public function setBrandIcon(?string $brandIcon): self
    {
        return $this->setData('brand_icon', $brandIcon);
    }

    public function getBrandAdditionalIcon(): ?string
    {
        return $this->getData('brand_additional_icon');
    }

    public function setBrandAdditionalIcon(?string $brandAdditionalIcon): self
    {
        return $this->setData('brand_additional_icon', $brandAdditionalIcon);
    }

    public function getUrlKey(): ?string
    {
        return $this->getData('brand_url_key');
    }

    public function setUrlKey(?string $urlKey): self
    {
        return $this->setData('brand_url_key', $urlKey);
    }

    public function getIsFeatured(): int
    {
        return (int)$this->getData('is_featured');
    }

    public function setIsFeatured(int $isFeatured): self
    {
        return $this->setData('is_featured', $isFeatured);
    }

    public function getShortDescription(): ?string
    {
        return $this->getData('short_description');
    }

    public function setShortDescription(?string $short): self
    {
        return $this->setData('short_description', $short);
    }

    public function getFullDescription(): ?string
    {
        return $this->getData('full_description');
    }

    public function setFullDescription(?string $full): self
    {
        return $this->setData('full_description', $full);
    }

    public function getEnabled(): int
    {
        return (int)$this->getData('enabled');
    }

    public function setEnabled(int $enabled): self
    {
        return $this->setData('enabled', $enabled);
    }

    public function getStoreId(): int
    {
        return (int)$this->getData('store_id');
    }

    public function setStoreId(int $storeId): self
    {
        return $this->setData('store_id', $storeId);
    }

    public function getShowInBrandCarousel(): int
    {
        return (int)$this->getData('show_in_brand_carousel');
    }

    public function setShowInBrandCarousel(int $isShown): self
    {
        return $this->setData('show_in_brand_carousel', $isShown);
    }

    public function getBrandIconUrl(?string $image = null): ?string
    {
        $icon = $this->getBrandIcon();

        if (!$icon) {
            $icon = $image;
        }

        if (!$icon) {
            return '';
        }

        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $baseUrl . $icon;
    }

    public function getBrandAdditionalIconUrl(?string $image = null): ?string
    {
        $icon = $this->getBrandAdditionalIcon();

        if (!$icon) {
            $icon = $image;
        }

        if (!$icon) {
            return '';
        }

        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $baseUrl . $icon;
    }

    public function getBrandUrl(?\Magento\Store\Api\Data\StoreInterface $store = null): string
    {
        $urlKey = $this->getUrlKey();

        if (!$urlKey) {
            return '';
        }

        $store ??= $this->storeManager->getStore();

        $brandUrlKey = $this->getResource()->getAttributeRawValue($this->getId(), 'brand_url_key', $store->getId());

        $urlKey = $brandUrlKey ?: $urlKey;

        $routeToBrand = $this->configuration->getRouteToBrand((int)$store->getId());

        if ($this->urlVerifier->isExternalUrl($urlKey)) {
            return $urlKey;
        }

        if (str_starts_with($urlKey, '/')) {
            return $store->getBaseUrl() . substr($urlKey, 1);
        }

        return $store->getBaseUrl() . $routeToBrand . '/' . $urlKey;
    }

    public function setBrandIconUrl(?string $brandIconUrl): self
    {
        return $this->setData('brand_icon_url', $brandIconUrl);
    }

    public function setBrandAdditionalIconUrl(?string $brandAdditionalIconUrl): self
    {
        return $this->setData('brand_additional_icon_url', $brandAdditionalIconUrl);
    }

    public function setBrandUrl(?string $brandUrl): self
    {
        return $this->setData('brand_url', $brandUrl);
    }

    public function getBrandIconEncodedData(): ?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface
    {
        return $this->getData('brand_icon_encoded_data');
    }

    public function setBrandIconEncodedData(?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandIcon): self
    {
        return $this->setData('brand_icon_encoded_data', $brandIcon);
    }

    public function getBrandAdditionalIconEncodedData(): ?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface
    {
        return $this->getData('brand_additional_icon_encoded_data');
    }

    public function setBrandAdditionalIconEncodedData(?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandAdditionalIcon): self
    {
        return $this->setData('brand_additional_icon_encoded_data', $brandAdditionalIcon);
    }

    public function getMetaTitle(): ?string
    {
        return $this->getData('meta_title');
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        return $this->setData('meta_title', $metaTitle);
    }

    public function getMetaDescription(): ?string
    {
        return $this->getData('meta_description');
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        return $this->setData('meta_description', $metaDescription);
    }

    public function getMetaRobots(): ?string
    {
        return $this->getData('meta_robots');
    }

    public function setMetaRobots(?string $metaRobots): self
    {
        return $this->setData('meta_robots', $metaRobots);
    }

    public function getSortOrder(): int
    {
        return (int)$this->getData('sort_order');
    }

    public function setSortOrder(int $sortOrder): self
    {
        return $this->setData('sort_order', $sortOrder);
    }

    public function getIsSearchable(): int
    {
        return (int)$this->getData('is_searchable');
    }

    public function setIsSearchable(int $value): self
    {
        return $this->setData('is_searchable', $value);
    }

    public function getIdentities(): array
    {
        return [
            self::CACHE_TAG . '_' . $this->getEntityId(),
        ];
    }

    protected function _getValidationRulesBeforeSave(): \MageSuite\BrandManagement\Model\Brands\Validator
    {
        return $this->validator;
    }
}
