<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\Component\Listing\Columns;

class Boolean extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $config = $this->getConfiguration();
            $name = $config['fieldName'] ?? $this->getName();

            foreach ($dataSource['data']['items'] as &$item) {
                $value = $item[$name] ?? false;
                $item[$name] = $value ? __('Yes') : __('No');
            }
        }

        return $dataSource;
    }
}
