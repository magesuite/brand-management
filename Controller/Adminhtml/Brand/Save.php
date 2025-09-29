<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Adminhtml\Brand;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const USE_DEFAULT_SCOPE = 'use_default';

    public function __construct(
        protected \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        protected \MageSuite\BrandManagement\Model\BrandsFactory $brandsFactory,
        protected \Magento\Framework\App\RequestInterface $request,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\Controller\Result\Redirect
    {
        $params = $this->request->getParams();
        $brandData = $params[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\BrandDataProvider::BRAND_DATA_SCOPE] ?? [];
        $brandData[\MageSuite\BrandManagement\Setup\Patch\Data\AddContentConstructorContentAttribute::ATTRIBUTE_CODE] = $params['components'] ?? null;

        $brandId = (int)($brandData['entity_id'] ?? null);
        $storeId = (int)($brandData['store_id'] ?? \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        $useDefault = $params[self::USE_DEFAULT_SCOPE] ?? [];

        $brand = $this->getBrand($brandId, $storeId);

        foreach ($brandData as $attributeCode => $value) {
            if (empty($useDefault[$attributeCode])) {
                $brand->setData($attributeCode, $value);

                continue;
            }

            $brand->setData($attributeCode);
        }

        try {
            $this->brandsRepository->save($brand);

            $this->messageManager->addSuccessMessage('Brand has been saved.');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->getRedirect($brand);
    }

    protected function getBrand(int $brandId, int $storeId): \MageSuite\BrandManagement\Api\Data\BrandsInterface
    {
        try {
            return $this->brandsRepository->getById($brandId, $storeId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException) {
            return $this->brandsFactory->create();
        }
    }

    public function getRedirect(\MageSuite\BrandManagement\Api\Data\BrandsInterface $brand): \Magento\Backend\Model\View\Result\Redirect
    {
        $redirect = $this->resultRedirectFactory->create();
        $redirectParams = [\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData::ENTITY_ID => $brand->getId()];

        $storeId = $brand->getStoreId();

        if ($storeId) {
            $redirectParams[\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData::STORE] = $storeId;
        }

        $redirect->setPath('brands/brand/edit', $redirectParams);

        return $redirect;
    }
}
