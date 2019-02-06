<?php
namespace MageSuite\BrandManagement\Model\Brands\Processor;

class Save
{
    const DEFAULT_STORE_ID = 0;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    /**
     * @var \MageSuite\BrandManagement\Model\BrandsFactory
     */
    protected $brandsFactory;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

    public function __construct(
        \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Framework\Event\Manager $eventManager
    )
    {
        $this->brandsFactory = $brandsFactory;
        $this->brandsRepository = $brandsRepository;
        $this->eventManager = $eventManager;
    }

    public function processSave($params) {
        $originalParams = $params;

        $isNew = (isset($params['entity_id']) && $params['entity_id'] == "") ? true : false;

        if ($isNew) {
            if(!isset($params['store_id'])){
                $params['store_id'] = self::DEFAULT_STORE_ID;
            }
            $brand = $this->brandsFactory->create();
            $brand
                ->setStoreId($params['store_id'])
                ->setUrlKey($params['brand_url_key'])
                ->setLayoutUpdateXml($params['layout_update_xml'])
                ->setBrandName($params['brand_name'])
                ->setEnabled($params['enabled'])
                ->setIsFeatured($params['is_featured'])
                ->setShowInBrandCarousel($params['show_in_brand_carousel']);
        } else {
            $matchedParams = $this->matchParams($params);

            $params = $matchedParams;

            $brand = $this->brandsRepository->getById($params['entity_id'], $params['store_id']);
            $brand->setData($params);
        }
        $imagePath = false;

        if(isset($params['brand_icon'])) {
            if (is_array($params['brand_icon'])) {
                $imagePath = $params['brand_icon'][0]['name'];
            } else {
                $imagePath = $params['brand_icon'];
            }
        }
        if($imagePath){
            $brand->setBrandIcon($imagePath);
        } elseif ($brand->getStoreId() == self::DEFAULT_STORE_ID) {
            $brand->setBrandIcon('');
        }

        $imageAdditionalPath = false;

        if(isset($params['brand_additional_icon'])) {
            if (is_array($params['brand_additional_icon'])) {
                $imageAdditionalPath = $params['brand_additional_icon'][0]['name'];
            } else {
                $imageAdditionalPath = $params['brand_additional_icon'];
            }
        }
        if($imageAdditionalPath){
            $brand->setBrandAdditionalIcon($imageAdditionalPath);
        } elseif ($brand->getStoreId() == self::DEFAULT_STORE_ID) {
            $brand->setBrandAdditionalIcon('');
        }

        $this->eventManager->dispatch('brand_prepare_save', ['brand' => $brand, 'params' => $originalParams]);

        $this->brandsRepository->save($brand);

        return $this;
    }

    public function matchChangedFields($config)
    {
        $matchedFields = [];
        foreach ($config as $field => $value) {
            if($value == 'false'){
                $matchedFields[] = $field;
            }
        }
        return $matchedFields;
    }

    public function matchParams($params)
    {
        $changedFields = $this->matchChangedFields($params['use_config']);

        $matchedParams = [];

        foreach ($changedFields as $field) {
            if(!isset($params[$field])) {
                continue;
            }

            if($field == 'brand_icon'){
                $matchedParams[$field] = $params['brand_icon'][0]['name'];
                continue;
            }

            if($field == 'brand_additional_icon'){
                $matchedParams[$field] = $params['brand_additional_icon'][0]['name'];
                continue;
            }

            $matchedParams[$field] = $params[$field];
        }
        $matchedParams['entity_id'] = $params['entity_id'];
        $matchedParams['store_id'] = $params['store_id'];

        return $matchedParams;
    }
}