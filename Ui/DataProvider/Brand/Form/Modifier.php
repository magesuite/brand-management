<?php

namespace MageSuite\BrandManagement\Ui\DataProvider\Brand\Form;

class Modifier extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        
        return $meta;
    }
}