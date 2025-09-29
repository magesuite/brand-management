<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Brands\ValidatorRules;

class RequiredFieldsAreNotEmpty extends \Magento\Framework\Validator\AbstractValidator
{
    public function __construct(
        protected array $requiredFields = []
    ) {}

    /**
     * @param \MageSuite\BrandManagement\Model\Brands $value
     */
    public function isValid($value): bool
    {
        $messages = [];

        foreach ($this->requiredFields as $requiredField) {
            if ($this->isRequiredFieldMissingInDefaultStore($value, $requiredField)) {
                $messages[] = __('Missing required field: %1', $requiredField);
            }
        }

        if (empty($messages)) {
            return true;
        }

        $this->_addMessages($messages);

        return false;
    }

    public function isRequiredFieldMissingInDefaultStore(\MageSuite\BrandManagement\Model\Brands $value, string $requiredField): bool
    {
        return empty($value->getData($requiredField))
            && $value->getStoreId() == \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }
}
