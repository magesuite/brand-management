<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Integration\Ui\DataProvider\Brand\Form\Modifier;

#[\Magento\TestFramework\Fixture\AppArea('adminhtml')]
class EavTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\ObjectManagerInterface $objectManager = null;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    public function testItPlacesAttributesInGroupsInConfiguredOrder(): void
    {
        $meta = $this->getMeta(null);

        $this->assertSame(['enabled', 'brand_name', 'brand_url_key', 'is_featured', 'sort_order', 'brand_group_identifier', 'show_in_brand_carousel'], $this->getAttributeCodes($meta['general']));
        $this->assertSame(['short_description', 'full_description', 'safety_regulations', 'hide_headline', 'brand_icon', 'brand_additional_icon'], $this->getAttributeCodes($meta['content']));
        $this->assertLessThan($meta['content']['arguments']['data']['config']['sortOrder'], $meta['general']['arguments']['data']['config']['sortOrder']);
    }

    public function testItMarksBrandNameAndUrlKeyAsRequired(): void
    {
        $meta = $this->getMeta(null);

        $this->assertSame(['required-entry' => true], $this->getFieldConfig($meta, 'brand_name')['validation']);
        $this->assertSame(['required-entry' => true], $this->getFieldConfig($meta, 'brand_url_key')['validation']);
        $this->assertSame([], $this->getFieldConfig($meta, 'is_featured')['validation']);
    }

    public function testItAllowsUrlKeyImportFromBrandNameForNewBrand(): void
    {
        $urlKeyConfig = $this->getFieldConfig($this->getMeta(null), 'brand_url_key');

        $this->assertSame('MageSuite_BrandManagement/js/form/element/url-key', $urlKeyConfig['component']);
        $this->assertSame('{{brand_name}}', $urlKeyConfig['mask']);
        $this->assertTrue($urlKeyConfig['allowImport']);
    }

    public function testItDisallowsUrlKeyImportForExistingBrand(): void
    {
        $urlKeyConfig = $this->getFieldConfig($this->getMeta(600), 'brand_url_key');

        $this->assertFalse($urlKeyConfig['allowImport']);
    }

    protected function getMeta(?int $brandId): array
    {
        $request = $this->objectManager->create(\Magento\TestFramework\Request::class);
        $request->setParam(\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData::ENTITY_ID, $brandId);

        $requestData = $this->objectManager->create(\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\RequestData::class, ['request' => $request]);
        $urlKeyConfigProvider = $this->objectManager->create(\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\AttributeConfig\UrlKey::class, ['requestData' => $requestData]);
        $eavModifier = $this->objectManager->create(\MageSuite\BrandManagement\Ui\DataProvider\Brand\Form\Modifier\Eav::class, [
            'requestData' => $requestData,
            'attributeConfigProviders' => ['brand_url_key' => $urlKeyConfigProvider],
        ]);

        return $eavModifier->modifyMeta([]);
    }

    protected function getAttributeCodes(array $groupMeta): array
    {
        return array_map(fn(array $container): string => array_key_first($container['children']), array_values($groupMeta['children']));
    }

    protected function getFieldConfig(array $meta, string $attributeCode): array
    {
        $containerName = sprintf('container_%s', $attributeCode);

        return $meta['general']['children'][$containerName]['children'][$attributeCode]['arguments']['data']['config'];
    }
}
