<?php
namespace MageSuite\BrandManagement\Validator;

class BrandParams
{
    public function validateParams($params)
    {
        foreach ($this->requiredFields() as $key => $value) {
            if(!isset($params[$key]) || empty($params[$key])) {
                throw new \InvalidArgumentException(sprintf(__('Field: %s is required field.'), $value));
            }
        }
        return true;
    }

    public function requiredFields()
    {
        return [
            'brand_name' => 'Brand Name',
            'brand_url_key' => 'Brand Url Key',
        ];
    }
}
