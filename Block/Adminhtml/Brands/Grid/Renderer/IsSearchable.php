<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

class IsSearchable extends \MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer\AbstractColumnRenderer
{
    /**
     * @inheritdoc
     */
    public function getColumnValue($columnId, $entityId): string
    {
        $entity = $this->getBrandData($entityId);

        if (!$entity->getEntityId()) {
            return '';
        }
        $value = $entity->getIsSearchable() ? __('Yes') : __('No');

        return (string) $value;
    }
}
