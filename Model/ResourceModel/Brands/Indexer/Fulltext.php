<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Model\ResourceModel\Brands\Indexer;

class Fulltext extends \Smile\ElasticsuiteCore\Model\ResourceModel\Indexer\AbstractIndexer
{
    protected ?array $searchableAttrIdsByTable = null;
    protected ?array $filterAttrIdsValueByTable = null;
    protected array $attributeByCode = [];

    protected array $filterAttrValues = [
        'enabled' => 1,
        'is_searchable' => 1,
    ];

    protected array $searchableAttrCodes = [
        'brand_name',
        'short_description',
        'full_description',
        'meta_title',
        'meta_description',
    ];

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\BrandManagement\Model\ResourceModel\Brands $brandsResourceModel,
    ) {
        parent::__construct($resource, $storeManager);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    // phpcs:ignore
    public function getSearchableBrandsData(
        int $storeId,
        ?array $entityIds = null,
        int $fromId = 0,
        int $limit = 100
    ): array {
        $connection = $this->getConnection();

        $entityTable = $this->brandsResourceModel->getEntityTable();
        $entityIdField = $this->brandsResourceModel->getEntityIdField();
        $tableIdField = sprintf('e.%s', $entityIdField);

        $select = $connection->select();
        $select->from(['e' => $entityTable], []);

        $this->addSearchableInStoreFilter($select, $storeId);

        if ($entityIds !== null) {
            $select->where($tableIdField . ' IN (?)', $entityIds);
        }
        $select->where($tableIdField . ' > ?', $fromId);
        $select->limit($limit);
        $select->order($tableIdField);
        $select->group($tableIdField);
        $select->columns([$entityIdField => $tableIdField]);

        $entityIds = $this->connection->fetchCol($select);
        $result = [];

        if (empty($entityIds)) {
            return $result;
        }

        foreach ($this->getSearchableAttrIdsByTable() as $table => $attrIds) {
            $attributesData = $this->getAttributesRawData($storeId, $entityIds, $table, $attrIds);

            foreach ($attributesData as $row) {
                $entityId = (int) $row[$entityIdField];
                $result[$entityId]['entity_id'] = $entityId;
                $result[$entityId][$row['attribute_code']] = $row['value'];
            }
        }

        return $result;
    }

    // phpcs:ignore
    protected function getAttributesRawData(
        int $storeId,
        array $entityIds,
        string $attributeTableName,
        array $attributeIds
    ): array {
        $connection = $this->getConnection();

        $linkField = $this->brandsResourceModel->getLinkField();
        $entityIdField = $this->brandsResourceModel->getEntityIdField();
        $entityTable = $this->brandsResourceModel->getEntityTable();

        $storeConditions = [
            'default' => $connection->quoteInto(
                't_attribute.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            ),
            'store' => $connection->quoteInto(
                't_attribute.store_id = ?',
                $storeId
            ),
        ];

        $result = [];

        foreach ($storeConditions as $condition) {
            $joinAttributeValuesCondition = [
                new \Zend_Db_Expr(sprintf('entity.%s = t_attribute.%s', $linkField, $linkField)),
                $condition,
            ];
            $joinAttributeValuesCondition = implode(' AND ', $joinAttributeValuesCondition);

            $select = $connection->select();
            $select->from(['entity' => $entityTable], [$entityIdField]);
            $select->joinLeft(
                ['t_attribute' => $attributeTableName],
                $joinAttributeValuesCondition,
                ['attribute_id', 'value']
            );
            $select->joinInner(
                ['attr' => $this->getTable('eav_attribute')],
                't_attribute.attribute_id = attr.attribute_id',
                ['attribute_id', 'attribute_code']
            );
            $select->where(sprintf('entity.%s IN (?)', $entityIdField), $entityIds);
            $select->where("t_attribute.attribute_id IN (?)", $attributeIds);
            $select->where("t_attribute.value IS NOT NULL");

            foreach ($connection->fetchAll($select) as $row) {
                $result[sprintf('%s-%s', $row['entity_id'], $row['attribute_id'])] = $row;
            }
        }

        return array_values($result);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addSearchableInStoreFilter(\Magento\Framework\DB\Select $select, int $storeId): void
    {
        $linkField = $this->brandsResourceModel->getLinkField();
        $entityLinkField = sprintf('e.%s', $linkField);
        $storeJoin = [
            's_' => $storeId,
            'd_' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
        ];

        foreach ($this->filterAttrValues as $code => $value) {
            $attribute = $this->getAttribute($code);
            $table = $attribute->getBackendTable();
            $attrId = (int) $attribute->getAttributeId();

            $valueCoalesce = [];

            foreach ($storeJoin as $prefix => $joinStoreId) {
                $attrTableAlias = $prefix . $code;
                $valueCoalesce[] = sprintf('%s.%s', $attrTableAlias, 'value');
                $cond = [
                    new \Zend_Db_Expr(sprintf('%s.%s = %s', $attrTableAlias, $linkField, $entityLinkField)),
                    new \Zend_Db_Expr(sprintf('%s.%s = %s', $attrTableAlias, 'attribute_id', $attrId)),
                    new \Zend_Db_Expr(sprintf('%s.%s = %s', $attrTableAlias, 'store_id', $joinStoreId)),
                ];
                $select->joinLeft([$attrTableAlias => $table], implode(' AND ', $cond), []);
            }
            $select->where(sprintf('COALESCE(%s) = ?', implode(' ,', $valueCoalesce)), $value);
        }
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getSearchableAttrIdsByTable(): array
    {
        if ($this->searchableAttrIdsByTable !== null) {
            return $this->searchableAttrIdsByTable;
        }
        $this->searchableAttrIdsByTable = [];

        foreach ($this->searchableAttrCodes as $code) {
            $attribute = $this->getAttribute($code);
            $table = $attribute->getBackendTable();
            $attrId = (int) $attribute->getAttributeId();
            $this->searchableAttrIdsByTable[$table][] = $attrId;
        }

        return $this->searchableAttrIdsByTable;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getAttribute(string $code): \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
    {
        if (!isset($this->attributeByCode[$code])) {
            $this->attributeByCode[$code] = $this->brandsResourceModel->getAttribute($code);
        }

        return $this->attributeByCode[$code];
    }
}
