<?php
namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Grid\Renderer;

abstract class AbstractColumnRenderer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var
     */
    protected $brand;

    /**
     * @var  \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var array
     */
    static $brandData = [];

    /**
     * Column constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository
     * @param \Magento\Framework\Filesystem $filesystem
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \Magento\Framework\Filesystem $filesystem,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->brandsRepository = $brandsRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return mixed|string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $column = $this->getColumn()->getIndex();

        $value = $this->getColumnValue($column, $row->getEntityId());

        return $value;
    }

    /**
     * @param $entityId
     * @return \MageSuite\BrandManagement\Api\Data\BrandsInterface
     */
    public function getBrandData($entityId)
    {
        if(!isset(self::$brandData[$entityId])) {
            self::$brandData[$entityId] = $this->brandsRepository->getById($entityId);
        }

        return self::$brandData[$entityId];
    }

    /**
     * @param $columnId
     * @param $entityId
     * @return mixed|string
     */
    abstract public function getColumnValue($columnId, $entityId);
}
