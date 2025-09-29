<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Brands\ValidatorRules;

class UniqueFieldsAreUnique extends \Magento\Framework\Validator\AbstractValidator
{
    public function __construct(
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResource,
        protected array $uniqueFields = []
    ) {}

    /**
     * @param \MageSuite\BrandManagement\Model\Brands $value
     */
    public function isValid($value): bool
    {
        $messages = [];

        foreach ($this->uniqueFields as $uniqueField) {
            $alreadyExists = $this->brandsResource->existsBrandWithSpecificAttributeValue($uniqueField, $value);

            if ($alreadyExists) {
                $messages[] = __('Brand with given %1 already exists.', $uniqueField);
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
