<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel;

/**
 * It is extending Catalog resource instead of Eav resource directly, because Catalog has attribute scope support, meanwhile Eav has not.
 * @see \Magento\Catalog\Model\ResourceModel\AbstractResource::_getLoadAttributesSelect
 * @see \Magento\Catalog\Model\ResourceModel\AbstractResource::_saveAttributeValue
 * @see \Magento\Catalog\Model\ResourceModel\AbstractResource::_deleteAttributes
 */
class Brands extends \Magento\Catalog\Model\ResourceModel\AbstractResource
{
    public function __construct( // phpcs:ignore
        protected \MageSuite\BrandManagement\Api\BrandAttributeRepositoryInterface $brandAttributeRepository,
        protected \MageSuite\BrandManagement\Model\GetDefaultAttributeSetId $getDefaultAttributeSetId,
        protected \Magento\Framework\UrlInterface $urlBuilder,
        protected array $attributesToTrim,
        \Magento\Eav\Model\Entity\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Factory $modelFactory,
        $data = [],
        ?\Magento\Eav\Model\Entity\Attribute\UniqueValidationInterface $uniqueValidator = null
    ) {
        parent::__construct($context, $storeManager, $modelFactory, $data, $uniqueValidator);
    }

    public function getEntityType(): ?\Magento\Eav\Model\Entity\Type
    {
        if (empty($this->_type)) {
            $this->setType(\MageSuite\BrandManagement\Model\Brands::ENTITY);
        }

        return parent::getEntityType();
    }

    public function getAttributeRawValue($entityId, $attribute, $store) // phpcs:ignore
    {
        $attribute = $this->getAttribute($attribute);
        $connection = $this->getConnection();
        $table = $attribute->getBackend()->getTable();
        $entityIdField = $this->getLinkField();
        $select = $connection->select()
            ->from($table, 'value')
            ->where($entityIdField . ' = ?', $entityId)
            ->where('store_id = ?', $store)
            ->where('attribute_id = ?', $attribute->getId());
        $result = $connection->fetchOne($select);

        return $result;
    }

    protected function _beforeSave(\Magento\Framework\DataObject $object) // phpcs:ignore
    {
        $this->extractImagesUrlFromImagesData($object);
        $this->addDefaultAttributeSetId($object);
        $this->trimWhiteCharacters($object);

        return parent::_beforeSave($object);
    }

    protected function extractImagesUrlFromImagesData(\Magento\Framework\DataObject $object): void
    {
        $imageAttributes = $this->brandAttributeRepository->getListByAttributeProperty('frontend_input', 'image')->getItems();

        foreach ($imageAttributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();

            $imageData = $object->getData($attributeCode);

            if (!is_array($imageData)) {
                continue;
            }

            $url = $imageData[0]['url'] ?? '';

            if (empty($url)) {
                $object->unsetData($attributeCode);

                continue;
            }

            $mediaUrl = $this->urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);

            $imagePath = str_replace($mediaUrl, '', $url);
            $object->setData($attributeCode, $imagePath);
        }
    }

    protected function _afterDelete(\Magento\Framework\DataObject $object) // phpcs:ignore
    {
        $this->clearSelectedOptionInEntities($object);

        parent::_afterDelete($object);
    }

    protected function clearSelectedOptionInEntities(\Magento\Framework\DataObject $object): void
    {
        $brandId = (int)$object->getId();
        $attribute = $this->_getConfig()->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand'
        );
        $connection = $this->getConnection();
        $where = [
            $connection->quoteInto('attribute_id = ?', $attribute->getId()),
            $connection->prepareSqlCondition('value', ['finset' => $brandId]),
        ];
        $concat = $connection->getConcatSql(["','", 'value', "','"]);
        $expr = $connection->quoteInto(
            "TRIM(BOTH ',' FROM REPLACE($concat,',?,',','))",
            $brandId
        );
        $connection->update(
            $attribute->getBackendTable(),
            ['value' => new \Zend_Db_Expr($expr)],
            implode(' AND ', $where)
        );
    }

    protected function addDefaultAttributeSetId(\Magento\Framework\DataObject $object): void
    {
        if ($object->hasData('attribute_set_id')) {
            return;
        }

        $attributeSetId = $this->getDefaultAttributeSetId->execute();

        $object->setData('attribute_set_id', $attributeSetId);
    }

    public function existsBrandWithSpecificAttributeValue(string $attributeCode, \MageSuite\BrandManagement\Model\Brands $brand): bool
    {
        $connection = $this->getConnection();

        $attribute = $this->getAttribute($attributeCode);
        $table = $attribute->getBackend()->getTable();
        $entityIdField = $this->getLinkField();

        $select = $connection->select()
            ->from($table, 'entity_id')
            ->where('value = ?', $brand->getData($attributeCode))
            ->where('store_id = ?', $brand->getStoreId())
            ->where('attribute_id = ?', $attribute->getId());

        if ($brand->getEntityId()) {
            $select->where($entityIdField . ' != ?', $brand->getEntityId());
        }

        return (bool)$connection->fetchOne($select);
    }

    protected function trimWhiteCharacters(\Magento\Framework\DataObject $object): void
    {
        foreach ($this->attributesToTrim as $attributeCode) {
            $value = $object->getData($attributeCode);

            if (is_string($value)) {
                $object->setData($attributeCode, trim($value));
            }
        }
    }
}
