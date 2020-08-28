<?php

namespace MageSuite\BrandManagement\Model;

class Brands extends \Magento\Catalog\Model\AbstractModel implements \MageSuite\BrandManagement\Api\Data\BrandsInterface, \Magento\Framework\DataObject\IdentityInterface
{

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'brands';
    const CACHE_TAG = 'brand';
    /**
     * Brand Store Id
     */
    const STORE_ID = 'store_id';
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'brand';
    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'brand';
    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;
    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;
    /**
     * Core data
     *
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $storeManager, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('MageSuite\BrandManagement\Model\ResourceModel\Brands');
    }

    /**
     * @return int|string|null
     */
    public function getEntityId()
    {
        return $this->getData('entity_id');
    }

    /**
     * @param int|string|null $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData('entity_id', $entityId);
    }

    /**
     * @return mixed
     */
    public function getBrandName()
    {
        return $this->getData('brand_name');
    }

    /**
     * @param string $brandName
     * @return $this
     */
    public function setBrandName($brandName)
    {
        return $this->setData('brand_name', $brandName);
    }

    /**
     * @return mixed
     */
    public function getLayoutUpdateXml()
    {
        return $this->getData('layout_update_xml');
    }

    /**
     * @param $xml
     * @return $this
     */
    public function setLayoutUpdateXml($xml)
    {
        return $this->setData('layout_update_xml', $xml);
    }

    /**
     * @return mixed
     */
    public function getBrandIcon()
    {
        return $this->getData('brand_icon');
    }

    /**
     * @param string[] $brandIcon
     * @return $this
     */
    public function setBrandIcon($brandIcon)
    {
        return $this->setData('brand_icon', $brandIcon);
    }

    /**
     * @return mixed
     */
    public function getBrandAdditionalIcon()
    {
        return $this->getData('brand_additional_icon');
    }

    /**
     * @param $brandAdditionalIcon
     * @return $this
     */
    public function setBrandAdditionalIcon($brandAdditionalIcon)
    {
        return $this->setData('brand_additional_icon', $brandAdditionalIcon);
    }

    /**
     * @return mixed
     */
    public function getUrlKey()
    {
        return $this->getData('brand_url_key');
    }

    /**
     * @param $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData('brand_url_key', $urlKey);
    }

    /**
     * @return int
     */
    public function getIsFeatured()
    {
        return $this->getData('is_featured');
    }

    /**
     * @param int $isFeatured
     * @return $this
     */
    public function setIsFeatured($isFeatured)
    {
        return $this->setData('is_featured', $isFeatured);
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->getData('short_description');
    }

    /**
     * @param $short
     * @return $this
     */
    public function setShortDescription($short)
    {
        return $this->setData('short_description', $short);
    }

    /**
     * @return mixed
     */
    public function getFullDescription()
    {
        return $this->getData('full_description');
    }

    /**
     * @param $full
     * @return $this
     */
    public function setFullDescription($full)
    {
        return $this->setData('full_description', $full);
    }

    /**
     * @return int
     */
    public function getEnabled()
    {
        return $this->getData('enabled');
    }

    /**
     * @param int $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        return $this->setData('enabled', $enabled);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData('store_id', $storeId);
    }

    /**
     * @return int
     */
    public function getShowInBrandCarousel()
    {
        return $this->getData('show_in_brand_carousel');
    }

    /**
     * @param int $isShown
     * @return $this
     */
    public function setShowInBrandCarousel($isShown)
    {
        return $this->setData('show_in_brand_carousel', $isShown);
    }

    public function getBrandIconUrl($image = null)
    {

        if (!$icon = $this->getBrandIcon()) {
            $icon = $image;
        }

        if (!$icon) {
            return '';
        }

        return $this->_storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'brands/' . $icon;
    }


    public function getBrandAdditionalIconUrl($image = null)
    {

        if (!$icon = $this->getBrandAdditionalIcon()) {
            $icon = $image;
        }

        if (!$icon) {
            return '';
        }

        return $this->_storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'brands/' . $icon;
    }

    public function getBrandUrl()
    {
        $url = '';

        if (!$urlKey = $this->getUrlKey()) {
            return $url;
        }

        if (preg_match("~^(?:f|ht)tps?://~i", $urlKey)) {
            $url = $urlKey;
        } elseif (substr($urlKey, 0, 1) === '/') {
            $url = $this->_storeManager
                    ->getStore()
                    ->getBaseUrl() . substr($urlKey, 1);
        } else {
            $url = $this->_storeManager
                    ->getStore()
                    ->getBaseUrl() . 'brands/' . $urlKey;
        }


        return $url;
    }

    /**
     * @param $brandIconUrl
     * @return mixed
     */
    public function setBrandIconUrl($brandIconUrl)
    {
        return $this->setData('brand_icon_url', $brandIconUrl);
    }

    /**
     * @param $brandAdditionalIconUrl
     * @return mixed
     */
    public function setBrandAdditionalIconUrl($brandAdditionalIconUrl)
    {
        return $this->setData('brand_additional_icon_url', $brandAdditionalIconUrl);
    }

    /**
     * @param $brandUrl
     * @return mixed
     */
    public function setBrandUrl($brandUrl)
    {
        return $this->setData('brand_url', $brandUrl);
    }

    /**
     * @return mixed
     */
    public function getBrandIconEncodedData()
    {
        return $this->getData('brand_icon_encoded_data');
    }

    /**
     * @param $brandIcon
     * @return mixed
     */
    public function setBrandIconEncodedData($brandIcon)
    {
        return $this->setData('brand_icon_encoded_data', $brandIcon);
    }

    /**
     * @return mixed
     */
    public function getBrandAdditionalIconEncodedData()
    {
        return $this->getData('brand_additional_icon_encoded_data');
    }

    /**
     * @param $brandAdditionalIcon
     * @return mixed
     */
    public function setBrandAdditionalIconEncodedData($brandAdditionalIcon)
    {
        return $this->setData('brand_additional_icon_encoded_data', $brandAdditionalIcon);
    }

    public function getMetaTitle()
    {
        return $this->getData('meta_title');
    }

    public function setMetaTitle($metaTitle)
    {
        return $this->setData('meta_title', $metaTitle);
    }

    public function getMetaDescription()
    {
        return $this->getData('meta_description');
    }

    public function setMetaDescription($metaDescription)
    {
        return $this->setData('meta_description', $metaDescription);
    }

    public function getMetaRobots()
    {
        return $this->getData('meta_robots');
    }

    public function setMetaRobots($metaRobots)
    {
        return $this->setData('meta_robots', $metaRobots);
    }

    public function getIdentities()
    {
        $identities = [
            self::CACHE_TAG . '_' . $this->getEntityId(),
        ];

        return $identities;
    }
}
