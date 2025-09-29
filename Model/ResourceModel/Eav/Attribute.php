<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Eav;

class Attribute extends \Magento\Eav\Model\Entity\Attribute implements \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface
{
    public const KEY_SCOPE = 'is_global';

    public function getScope(): int
    {
        return (int)$this->_getData(self::KEY_SCOPE);
    }

    public function setScope(int $scope): self
    {
        return $this->setData(self::KEY_SCOPE, $scope);
    }

    /**
     * Compatibility with Magento Catalog (product edit form). Do not remove even if no direct usages are found.
     */
    public function isScopeGlobal(): bool
    {
        return $this->getScope() == self::SCOPE_GLOBAL;
    }

    /**
     * Compatibility with Magento Catalog (product edit form). Do not remove even if no direct usages are found.
     */
    public function isScopeStore(): bool
    {
        return $this->getScope() == self::SCOPE_STORE;
    }
}
