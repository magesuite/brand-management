<?php

namespace MageSuite\BrandManagement\Model;

class BrandsRepository implements \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
{
    const DEFAULT_STORE_ID = 0;

    /** @var ResourceModel\Brands */
    protected $brandsResource;
    /**
     * @var BrandsFactory
     */
    protected $brandsFactory;

    /**
     * @var ResourceModel\Brands\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Brands[]
     */
    protected $instances = [];

    protected $brandAttributes = [
        'entity_id',
        'store_id',
        'brand_name',
        'brand_url_key',
        'layout_update_xml',
        'is_featured',
        'enabled',
        'show_in_brand_carousel',
        'brand_icon',
        'brand_additional_icon',
        'short_description',
        'full_description',
        'meta_title',
        'meta_description',
        'meta_robots'
    ];
    /**
     * @var Brands\Processor\SaveFactory
     */
    private $saveFactory;
    /**
     * @var \MageSuite\BrandManagement\Validator\BrandParams
     */
    private $brandParamsValidator;
    /**
     * @var Brands\Processor\UploadFactory
     */
    private $uploadFactory;

    public function __construct(
        \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResource,
        BrandsFactory $brandsFactory,
        \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\BrandManagement\Model\Brands\Processor\SaveFactory $saveFactory,
        \MageSuite\BrandManagement\Validator\BrandParams $brandParamsValidator,
        \MageSuite\BrandManagement\Model\Brands\Processor\UploadFactory $uploadFactory
    )
    {
        $this->brandsFactory = $brandsFactory;
        $this->brandsResource = $brandsResource;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->saveFactory = $saveFactory;
        $this->brandParamsValidator = $brandParamsValidator;
        $this->uploadFactory = $uploadFactory;
    }

    public function getById($id, $storeId = null)
    {
        /** @var Brands $brand */
        $brand = $this->brandsFactory->create();
        if (null !== $storeId) {
            $brand->setData('store_id', $storeId);
        }

        /**
         * Brand is loaded twice, first for requested store id, second for default store view.
         * This is done to get proper data if params for store id is empty or not fulfilled.
         * If brand is not additionally loaded for default store view data is not taken correctly.
         */
        $brand->getResource()->setDefaultStoreId($storeId);
        $brand->load($id);
        $brand->getResource()->setDefaultStoreId(self::DEFAULT_STORE_ID);
        $brand->load($id);

        if (!$brand->getEntityId()) {
            return null;
        }

        return $brand;
    }


    public function save(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand)
    {
        try {
            $isExists = ($this->getById($brand['entity_id'])) ? true : false;
            if (!$isExists) {
                $this->brandsResource->save($brand);
            }

            $attributesToRemove = $this->brandAttributes;
            foreach ($brand->getData() as $key => $value) {
                $attr = $this->brandsResource->getAttribute($key);
                $attributeIndex = array_search($key, $attributesToRemove);
                if (false !== $attributeIndex) {
                    unset($attributesToRemove[$attributeIndex]);
                }
                if (!$attr) {
                    continue;
                }
                $this->brandsResource->updateAttribute($brand, $attr, $value, $brand->getStoreId());
            }

            $this->brandsResource->removeAttribute($brand, $attributesToRemove);
            $brand->afterSave();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save brand: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        return $brand;
    }

    public function delete(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand)
    {
        $brandFactory = $this->brandsFactory->create();
        $brandFactory->setId($brand->getEntityId());

        try {
            $this->brandsResource->delete($brandFactory);
            return true;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __(
                    'Could not delete brand: %1',
                    $e->getMessage()
                ),
                $e
            );
        }

    }

    public function getAllBrands($storeId = null)
    {
        if($storeId == null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $brandCollection = $this->collectionFactory->create();
        $brandCollection->setStoreId($storeId);
        $brandCollection->addAttributeToSelect('*');

        $brandDataArray = [];
        foreach ($brandCollection as $brand) {
            $brandDataArray[$brand->getEntityId()] = $brand;
        }

        return $brandDataArray;
    }

    public function getBrandByUrlKey($brandUrlKey, $storeId)
    {
        $allBrands = $this->getAllBrands($storeId);
        foreach ($allBrands as $brand) {
            if ($brand->getBrandUrlKey() == $brandUrlKey) {
                return $brand;
            }
        }

        return null;
    }

    public function create(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand)
    {
        try {
            $brand['is_api'] = true;
            $uploader = $this->uploadFactory->create();

            if($brand->getBrandIconEncodedData()) {
                $data = [
                    'imageData' => $brand->getBrandIconEncodedData(),
                    'type' => 'brand_icon'
                ];
                $brand->setBrandIcon($uploader->processUpload($data));
            }

            if($brand->getBrandAdditionalIconEncodedData()) {
                $data = [
                    'imageData' => $brand->getBrandAdditionalIconEncodedData(),
                    'type' => 'brand_additional_icon'
                ];
                $brand->setBrandAdditionalIcon($uploader->processUpload($data));
            }

            $this->brandParamsValidator->validateParams($brand);

            $brand = $this->saveFactory->create()->processSave($brand);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Could not save brand.', $e->getMessage()), $e);
        }
        return $brand;
    }

    public function update(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand)
    {
        $storeId = $this->storeManager->getStore()->getId();

        $brandEntity = $this->getById($brand->getEntityId(), $storeId);

        $brandEntity->addData($brand->getData());

        return $this->create($brandEntity);
    }

    public function deleteById($id)
    {
        $brand = $this->getById($id);
        $this->delete($brand);
    }
}
