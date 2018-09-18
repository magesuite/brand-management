<?php

namespace MageSuite\BrandManagement\Api\Data;

interface BrandsInterface
{
    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
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
     * @return mixed
     */
    public function getIsFeatured();

    /**
     * @param $isFeatured
     * @return mixed
     */
    public function setIsFeatured($isFeatured);

    /**
     * @return mixed
     */
    public function getEnabled();

    /**
     * @param $enabled
     * @return mixed
     */
    public function setEnabled($enabled);


    /**
     * @return mixed
     */
    public function getStoreId();

    /**
     * @param $storeId
     * @return mixed
     */
    public function setStoreId($storeId);

    /**
     * @return string
     */
    public function getBrandIconUrl();

    /**
     * @return string
     */
    public function getBrandAdditionalIconUrl();

    /**
     * @return string
     */

    public function getBrandUrl();

    /**
     * @param $isShown
     * @return string
     */
    public function setShowInBrandCarousel($isShown);

    /**
     * @return string
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
}