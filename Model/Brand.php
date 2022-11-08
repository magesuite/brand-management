<?php

namespace MageSuite\BrandManagement\Model;

class Brand
{
    const BRAND_ATTRIBUTE_CODE = 'brand';
    const BRAND_GROUP_IDENTIFIER_ATTRIBUTE_CODE = 'brand_group_identifier';

    public static array $additionalFields = [
        self::BRAND_GROUP_IDENTIFIER_ATTRIBUTE_CODE
    ];
}
