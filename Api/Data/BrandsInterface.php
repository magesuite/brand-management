<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Api\Data;

interface BrandsInterface
{
    /**
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * @param int|null $entityId
     * @return self
     */
    public function setEntityId(?int $entityId): self;

    /**
     * @return string|null
     */
    public function getBrandName(): ?string;

    /**
     * @param string|null $brandName
     * @return self
     */
    public function setBrandName(?string $brandName): self;

    /**
     * @return string|null
     */
    public function getLayoutUpdateXml(): ?string;

    /**
     * @param string|null $xml
     * @return self
     */
    public function setLayoutUpdateXml(?string $xml): self;

    /**
     * @return string|null
     */
    public function getBrandIcon(): ?string;

    /**
     * @param string|null $brandIcon
     * @return self
     */
    public function setBrandIcon(?string $brandIcon): self;

    /**
     * @return string|null
     */
    public function getBrandAdditionalIcon(): ?string;

    /**
     * @param string|null $brandAdditionalIcon
     * @return self
     */
    public function setBrandAdditionalIcon(?string $brandAdditionalIcon): self;

    /**
     * @return string|null
     */
    public function getUrlKey(): ?string;

    /**
     * @param string|null $urlKey
     * @return self
     */
    public function setUrlKey(?string $urlKey): self;

    /**
     * @return int
     */
    public function getIsFeatured(): int;

    /**
     * @param int $isFeatured
     * @return self
     */
    public function setIsFeatured(int $isFeatured): self;

    /**
     * @return int
     */
    public function getEnabled(): int;

    /**
     * @param int $enabled
     * @return self
     */
    public function setEnabled(int $enabled): self;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $storeId
     * @return self
     */
    public function setStoreId(int $storeId): self;

    /**
     * @return string|null
     */
    public function getBrandIconUrl(): ?string;

    /**
     * @param string|null $brandIconUrl
     * @return self
     */
    public function setBrandIconUrl(?string $brandIconUrl): self;

    /**
     * @return string|null
     */
    public function getBrandAdditionalIconUrl(): ?string;

    /**
     * @param string|null $brandAdditionalIconUrl
     * @return self
     */
    public function setBrandAdditionalIconUrl(?string $brandAdditionalIconUrl): self;

    /**
     * @return string
     */
    public function getBrandUrl(): string;

    /**
     * @param string $brandUrl
     * @return self
     */
    public function setBrandUrl(string $brandUrl): self;

    /**
     * @param int $isShown
     * @return self
     */
    public function setShowInBrandCarousel(int $isShown): self;

    /**
     * @return int
     */
    public function getShowInBrandCarousel(): int;

    /**
     * @param string|null $short
     * @return self
     */
    public function setShortDescription(?string $short): self;

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string;

    /**
     * @param string|null $full
     * @return self
     */
    public function setFullDescription(?string $full): self;

    /**
     * @return string|null
     */
    public function getFullDescription(): ?string;

    /**
     * @return \MageSuite\BrandManagement\Api\Data\BrandImagesInterface|null
     */
    public function getBrandIconEncodedData(): ?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandImagesInterface|null $brandIcon
     * @return self
     */
    public function setBrandIconEncodedData(?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandIcon): self;

    /**
     * @return \MageSuite\BrandManagement\Api\Data\BrandImagesInterface|null
     */
    public function getBrandAdditionalIconEncodedData(): ?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface;

    /**
     * @param \MageSuite\BrandManagement\Api\Data\BrandImagesInterface|null $brandAdditionalIcon
     * @return self
     */
    public function setBrandAdditionalIconEncodedData(?\MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandAdditionalIcon): self;

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * @param string|null $metaTitle
     * @return self
     */
    public function setMetaTitle(?string $metaTitle): self;

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * @param string|null $metaDescription
     * @return self
     */
    public function setMetaDescription(?string $metaDescription): self;

    /**
     * @return string|null
     */
    public function getMetaRobots(): ?string;

    /**
     * @param string|null $metaRobots
     * @return self
     */
    public function setMetaRobots(?string $metaRobots): self;

    /**
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * @param int $sortOrder
     * @return self
     */
    public function setSortOrder(int $sortOrder): self;

    /**
     * @return int
     */
    public function getIsSearchable(): int;

    /**
     * @param int $value
     * @return self
     */
    public function setIsSearchable(int $value): self;
}
