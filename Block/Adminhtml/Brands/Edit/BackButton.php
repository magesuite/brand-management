<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Edit;

class BackButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function __construct(
        protected \Magento\Framework\UrlInterface $url,
    ) {}

    public function getButtonData(): array
    {
        $backUrl = $this->url->getUrl('*/grid/');

        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $backUrl),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
