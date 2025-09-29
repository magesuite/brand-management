<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Edit;

class DeleteButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function __construct(
        protected \Magento\Framework\UrlInterface $url,
        protected \MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData $requestData,
    ) {}

    public function getButtonData(): array
    {
        $brandId = $this->requestData->getBrandId();

        if (!$brandId) {
            return [];
        }

        $deleteUrl = $this->url->getUrl('*/brand/delete', [\MageSuite\BrandManagement\Controller\Adminhtml\Brand\Delete::PARAM_ENTITY_ID => $brandId]);

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => sprintf("deleteConfirm('%s', '%s')", __('Are you sure you want to do this?'), $deleteUrl),
            'sort_order' => 20,
        ];
    }
}
