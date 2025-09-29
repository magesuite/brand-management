<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Adminhtml\Button;

class NewBrand implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function __construct(
        protected \Magento\Framework\UrlInterface $url,
    ) {}

    public function getButtonData(): array
    {
        return [
            'label' => __('New Brand'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl()),
            'class' => 'primary',
            'sort_order' => 10
        ];
    }

    public function getUrl(): string
    {
        return $this->url->getUrl(
            '*/brand/edit'
        );
    }
}
