<?php
/** @var $store \Magento\Store\Model\Store */
$store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');
if (!$store->load('test333', 'code')->getId()) {
    $store->setData(
        [
            'code' => 'test333',
            'website_id' => '1',
            'group_id' => '1',
            'name' => 'Test Store 333',
            'sort_order' => '0',
            'is_active' => '1',
        ]
    );
    $store->save();
}
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/* Refresh stores memory cache */
$objectManager->get('Magento\Store\Model\StoreManagerInterface')->reinitStores();
