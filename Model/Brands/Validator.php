<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\Brands;

class Validator extends \Magento\Framework\Validator\AbstractValidator
{
    public function __construct(
        protected \Magento\Framework\Validator\Factory $validatorFactory,
    ) {}

    /**
     * @param \MageSuite\BrandManagement\Model\Brands $value
     */
    public function isValid($value): bool
    {
        $validator = $this->validatorFactory->createValidator('brands', 'save_brand');

        if ($validator->isValid($value)) {
            return true;
        }

        $this->_addMessages($validator->getMessages());

        return false;
    }
}


