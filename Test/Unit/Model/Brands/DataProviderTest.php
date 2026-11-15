<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Test\Unit\Model\Brands;

class DataProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testItOnlyReferencesContainersDefinedInTheEditForm(): void
    {
        $request = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $request->method('getParams')->willReturn([]);

        $collectionFactory = $this->createMock(
            \MageSuite\BrandManagement\Model\ResourceModel\Brands\CollectionFactory::class
        );
        $collectionFactory->method('create')->willReturn(
            $this->createMock(\MageSuite\BrandManagement\Model\ResourceModel\Brands\Collection::class)
        );

        $dataProvider = new \MageSuite\BrandManagement\Model\Brands\DataProvider(
            'brands_edit_form_data_source',
            'entity_id',
            'id',
            $collectionFactory,
            $this->createMock(\MageSuite\BrandManagement\Api\BrandsRepositoryInterface::class),
            $this->createMock(\MageSuite\BrandManagement\Api\Data\BrandsInterfaceFactory::class),
            $request,
            $this->createMock(\Magento\Framework\Registry::class)
        );

        $form = simplexml_load_file(dirname(__DIR__, 4) . '/view/adminhtml/ui_component/brands_edit_form.xml');

        foreach ($dataProvider->getMeta() as $fieldsetName => $fieldset) {
            $containers = [];

            foreach ($form->xpath(sprintf('//fieldset[@name="%s"]//container', $fieldsetName)) as $container) {
                $containers[] = (string)$container['name'];
            }

            foreach (array_keys($fieldset['children']) as $containerName) {
                $this->assertContains(
                    $containerName,
                    $containers,
                    sprintf('Container "%s" is not defined in fieldset "%s"', $containerName, $fieldsetName)
                );
            }
        }
    }
}
