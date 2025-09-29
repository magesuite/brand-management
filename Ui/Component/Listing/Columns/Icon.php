<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\Component\Listing\Columns;

class Icon extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        protected \MageSuite\BrandManagement\Model\BrandUrlProvider $brandUrlProvider,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as & $item) {
                $previewImage = $item[$fieldName] ?? '';

                if (empty($previewImage)) {
                    continue;
                }

                $imageSrc = $this->brandUrlProvider->get($previewImage);
                $item[$fieldName . '_src'] = $imageSrc;
                $item[$fieldName . '_alt'] = $this->getAlt($item);
                $item[$fieldName . '_link'] = null;
                $item[$fieldName . '_orig_src'] = $imageSrc;
            }
        }

        return $dataSource;
    }

    protected function getAlt(array $row): ?string
    {
        $altField = $this->getData('config/altField');

        return $row[$altField] ?? null;
    }
}
