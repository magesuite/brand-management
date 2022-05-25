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

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var \MageSuite\BrandManagement\Model\ResourceModel\Brands
     */
    protected $brandResource;

    protected \Magento\Framework\Filter\FilterManager $filter;

    protected \MageSuite\BrandManagement\Model\UrlVerifier $urlVerifier;

    public function __construct(
        \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandResource,
        \Magento\Framework\Filter\FilterManager $filter,
        \MageSuite\BrandManagement\Model\UrlVerifier $urlVerifier
    ) {
        $this->brandsFactory = $brandsFactory;
        $this->brandsRepository = $brandsRepository;
        $this->eventManager = $eventManager;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->brandResource = $brandResource;
        $this->filter = $filter;
        $this->urlVerifier = $urlVerifier;
    }

    public function processSave($params)
    {
        $originalParams = $params;

        $isNew = (!isset($params['entity_id'])) || (isset($params['entity_id']) && $params['entity_id'] == "") ? true : false;

        if ($isNew) {
            if (!isset($params['store_id'])) {
                $params['store_id'] = self::DEFAULT_STORE_ID;
            }
            $brand = $this->brandsFactory->create();
            $brand->setData($params->getData());
        } else {
            if (!$params['is_api']) {
                $matchedParams = $this->matchParams($params);

                $params = $matchedParams;
            }

            $brand = $this->brandsRepository->getById($params['entity_id'], $params['store_id']);
            $brand->setData($params->getData());
        }

        $this->validateParameters($brand);

        $imagePath = false;

        if (isset($params['brand_icon'])) {
            if (is_array($params['brand_icon'])) {
                $imagePath = $params['brand_icon'][0]['name'];
            } else {
                $imagePath = $params['brand_icon'];
            }
        }
        if ($imagePath) {
            $brand->setBrandIcon($imagePath);
        } elseif ($brand->getStoreId() == self::DEFAULT_STORE_ID) {
            $brand->setBrandIcon('');
        }

        $imageAdditionalPath = false;

        if (isset($params['brand_additional_icon'])) {
            if (is_array($params['brand_additional_icon'])) {
                $imageAdditionalPath = $params['brand_additional_icon'][0]['name'];
            } else {
                $imageAdditionalPath = $params['brand_additional_icon'];
            }
        }
        if ($imageAdditionalPath) {
            $brand->setBrandAdditionalIcon($imageAdditionalPath);
        } elseif ($brand->getStoreId() == self::DEFAULT_STORE_ID) {
            $brand->setBrandAdditionalIcon('');
        }

        $urlKey = (string)$brand->getUrlKey();

        if ($urlKey && !$this->urlVerifier->isExternalUrl($urlKey) && substr($urlKey, 0, 1) !== '/') {
            $brand->setUrlKey($this->formatUrlKey($urlKey));
        }

        $this->eventManager->dispatch('brand_prepare_save', ['brand' => $brand, 'params' => $originalParams]);

        $brand = $this->brandsRepository->save($brand);

        return $brand;
    }

    public function matchChangedFields($config)
    {
        $matchedFields = [];
        foreach ($config as $field => $value) {
            if ($value == 'false') {
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
            if (!isset($params[$field])) {
                continue;
            }

            if ($field == 'brand_icon') {
                $matchedParams[$field] = $params['brand_icon'][0]['name'];
                continue;
            }

            if ($field == 'brand_additional_icon') {
                $matchedParams[$field] = $params['brand_additional_icon'][0]['name'];
                continue;
            }

            $matchedParams[$field] = $params[$field];
        }
        $matchedParams['entity_id'] = $params['entity_id'];
        $matchedParams['store_id'] = $params['store_id'];

        return $this->dataObjectFactory->create()->setData($matchedParams);
    }

    protected function validateParameters($brand)
    {
        if ($this->brandResource->existsBrandWithSpecificAttributeValue('brand_name', $brand)) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Brand with %1 name already exist!', $brand->getBrandName()));
        }

        if ($this->brandResource->existsBrandWithSpecificAttributeValue('brand_url_key', $brand)) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Brand with %1 url_key already exist!', $brand->getBrandUrlKey()));
        }
    }

    public function formatUrlKey(string $str): string
    {
        return $this->filter->translitUrl($str);
    }
}
