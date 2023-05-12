<?php

/** @var \Magento\Framework\Registry $registry */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

foreach ([555, 556, 557, 558, 559, 560] as $productId) {
    /** @var $product \Magento\Catalog\Model\Product */
    $product = $objectManager->create('Magento\Catalog\Model\Product');
    $product->load($productId);
    if ($product->getId()) {
        $product->delete();
    }
}

foreach ([334] as $categoryId) {
    /** @var $category \Magento\Catalog\Model\Category */
    $category = $objectManager->create('Magento\Catalog\Model\Category');
    $category->load($categoryId);
    if ($category->getId()) {
        $category->delete();
    }
}

/** @var $product \Magento\Catalog\Model\Product */
$attribute = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
$attribute->get('brand');
if ($attribute->getAttributeCode()) {
    $attribute->delete();
}
