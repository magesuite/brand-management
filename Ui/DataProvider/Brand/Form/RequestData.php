<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form;

class RequestData
{
    public const ENTITY_ID = 'entity_id';
    public const STORE = 'store';

    public function __construct(
        protected \Magento\Framework\App\RequestInterface $request,
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
    ) {}

    public function getStoreId(): int
    {
        return (int)$this->request->getParam(self::STORE);
    }

    public function getBrandId(): ?int
    {
        $brandId = $this->request->getParam(self::ENTITY_ID);

        return $brandId ? (int)$brandId : null;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBrand(): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        $brandId = $this->getBrandId();

        if (!$brandId) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Brand %1 not found.'), $brandId);
        }

        $storeId = $this->getStoreId();

        return $this->brandsRepository->getById($brandId, $storeId);
    }
}
