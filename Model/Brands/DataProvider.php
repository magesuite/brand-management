<?php
namespace MageSuite\BrandManagement\Model\Brands;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    const DEFAULT_STORE_ID = 0;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    /**
     * @var \MageSuite\BrandManagement\Api\Data\BrandsInterfaceFactory
     */
    protected $brandsFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $requestScopeFieldName = 'store';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsCollectionFactory
     * @param \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $brandsCollectionFactory,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \MageSuite\BrandManagement\Api\Data\BrandsInterfaceFactory $brandsFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry,
        array $meta = [],
        array $data = []
    ) {

        $this->brandsRepository = $brandsRepository;
        $this->brandsFactory = $brandsFactory;
        $this->collection = $brandsCollectionFactory->create();
        $this->request = $request;
        $this->registry = $registry;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get current brand
     *
     * @return MageSuite\BrandManagement\Model\Brands
     */
    public function getCurrentBrand()
    {
        $brand = $this->registry->registry('brand');
        if ($brand) {
            return $brand;
        }
        $requestId = $this->request->getParam($this->requestFieldName);
        $requestScope = $this->request->getParam($this->requestScopeFieldName, \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        if ($requestId) {
            $brand = $this->brandsRepository->getById($requestId, $requestScope);
        } else {
            $brand = $this->brandsFactory->create();
        }

        return $brand;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $result = [];
        $brand = $this->getCurrentBrand();

        if (!$brand || !$brand->getId()) {
            return $result;
        }

        $useConfig = [];
        $brandResource = $brand->getResource();

        if($brand->getStoreId() === \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
            $useConfig = [
                'brand_name' => false,
                'layout_update_xml' => false,
                'brand_url_key' => false,
                'is_featured' => false,
                'enabled' => false,
                'brand_icon' => false,
                'brand_additional_icon' => false,
                'show_in_brand_carousel' => false,
                'short_description' => false,
                'full_description' => false
            ];
        } else {
            foreach ($brand->getData() as $attrName => $value) {
                if ($attrName == 'entity_id' || $attrName == 'store_id') {
                    continue;
                }

                $rawValue = $brandResource->getAttributeRawValue($brand->getEntityId(), $attrName, $brand->getStoreId());
                $useConfigValue = $rawValue === false;
                $useConfig[$attrName] = $useConfigValue;
            }
        }
        $result = [
            $brand->getEntityId() => [
                'entity_id' => $brand->getEntityId(),
                'store_id' => $brand->getStoreId(),
                'brand_name' => $brand->getBrandName(),
                'layout_update_xml' => $brand->getLayoutUpdateXml(),
                'brand_url_key' => $brand->getUrlKey(),
                'is_featured' => $brand->getIsFeatured(),
                'enabled' => $brand->getEnabled(),
                'show_in_brand_carousel' => $brand->getShowInBrandCarousel(),
                'short_description' => $brand->getShortDescription(),
                'full_description' => $brand->getFullDescription(),
                'use_config' => $useConfig
            ]
        ];

        if($brand->getBrandIcon()){
            $name = $brand->getBrandIcon();
            $url = $brand->getBrandIconUrl();
            $size = file_exists('media/brands/' . $name) ? filesize('media/brands/' . $name) : 0;
            $result[$brand->getEntityId()]['brand_icon'] = [
                0 => [
                    'url' => $url,
                    'name' => $name,
                    'size' => $size
                ]
            ];
        }

        if($brand->getBrandAdditionalIcon()){
            $name = $brand->getBrandAdditionalIcon();
            $url = $brand->getBrandAdditionalIconUrl();
            $size = file_exists('media/brands/' . $name) ? filesize('media/brands/' . $name) : 0;
            $result[$brand->getEntityId()]['brand_additional_icon'] = [
                0 => [
                    'url' => $url,
                    'name' => $name,
                    'size' => $size
                ]
            ];
        }

        return $result;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();
        $params = $this->request->getParams();
        $groupsToFields = [
            'enabled_group' => 'use_config.enabled',
            'brand_name_group' => 'use_config.brand_name',
            'brand_icon_group' => 'use_config.brand_icon',
            'brand_url_key_group' => 'use_config.brand_url_key',
            'is_featured_group' => 'use_config.is_featured',
            'layout_update_xml_group' => 'use_config.layout_update_xml',
            'show_in_brand_carousel_group' => 'use_config.show_in_brand_carousel',
            'short_description_group' => 'use_config.short_description',
            'full_description_group' => 'use_config.full_description'
        ];

        if(!isset($params['store']) OR (isset($params['store']) and $params['store'] == '0')) {
            foreach($groupsToFields as $group => $field) {
                $meta['brand_details']['children'][$group]['children'][$field]['arguments']['data']['config']['visible'] = false;
                $meta['brand_details']['children'][$group]['children'][$field]['arguments']['data']['config']['default'] = false;
            }
        }

        return $meta;
    }
}
