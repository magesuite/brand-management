<?php

namespace MageSuite\BrandManagement\Api\Data;

interface BrandsInterface
{
    /**
     * @return int|string|null
     */
    public function getEntityId();

    /**
     * @param int|string|null $entityId
     * @return void
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getBrandName();

    /**
     * @param string $brandName
     * @return void
     */
    public function setBrandName($brandName);


    /**
     * @return mixed
     */
    public function getLayoutUpdateXml();

    /**
     * @param $xml
     * @return mixed
     */
    public function setLayoutUpdateXml($xml);

    /**
     * @return mixed
     */
    public function getBrandIcon();

    /**
     * @param $brandIcon
     * @return mixed
     */
    public function setBrandIcon($brandIcon);

    /**
     * @return mixed
     */
    public function getBrandAdditionalIcon();

    /**
     * @param $brandAdditionalIcon
     * @return mixed
     */
    public function setBrandAdditionalIcon($brandAdditionalIcon);


    /**
     * @return mixed
     */
    public function getUrlKey();

    /**
     * @param $urlKey
     * @return mixed
     */
    public function setUrlKey($urlKey);

    /**
     * @return int
     */
    public function getIsFeatured();

    /**
     * @param int $isFeatured
     * @return mixed
     */
    public function setIsFeatured($isFeatured);

    /**
     * @return int
     */
    public function getEnabled();

    /**
     * @param int $enabled
     * @return mixed
     */
    public function setEnabled($enabled);


    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return mixed
     */
    public function setStoreId($storeId);

    /**
     * @return string
     */
    public function getBrandIconUrl();

    /**
     * @param $brandIconUrl
     * @return mixed
     */
    public function setBrandIconUrl($brandIconUrl);

    /**
     * @return string
     */
    public function getBrandAdditionalIconUrl();

    /**
     * @param $brandAdditionalIconUrl
     * @return mixed
     */
    public function setBrandAdditionalIconUrl($brandAdditionalIconUrl);

    /**
     * @return string
     */
    public function getBrandUrl();

    /**
     * @param $brandUrl
     * @return mixed
     */
    public function setBrandUrl($brandUrl);

    /**
     * @param int $isShown
     * @return string
     */
    public function setShowInBrandCarousel($isShown);

    /**
     * @return int
     */
    public function getShowInBrandCarousel();

    /**
     * @param $short
     * @return string
     */
    public function setShortDescription($short);

    /**
     * @return string
     */
    public function getShortDescription();

    /**
     * @param $full
     * @return string
     */
    public function setFullDescription($full);

    /**
     * @return string
     */
    public function getFullDescription();

    /**
     * @return \MageSuite\BrandManagement\Api\Data\BrandImagesInterface
     */
    public function getBrandIconEncodedData();

    /**
     * @param @param \MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandIcon
     * @return mixed
     */
    public function setBrandIconEncodedData($brandIcon);

    /**
     * @return \MageSuite\BrandManagement\Api\Data\BrandImagesInterface
     */
    public function getBrandAdditionalIconEncodedData();

    /**
     * @param @param \MageSuite\BrandManagement\Api\Data\BrandImagesInterface $brandAdditionalIcon
     * @return mixed
     */
    public function setBrandAdditionalIconEncodedData($brandAdditionalIcon);
}