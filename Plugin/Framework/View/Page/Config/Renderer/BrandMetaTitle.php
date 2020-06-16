<?php
namespace MageSuite\BrandManagement\Plugin\Framework\View\Page\Config\Renderer;

class BrandMetaTitle
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    public function __construct(\Magento\Framework\Registry $registry, \Magento\Framework\Escaper $escaper)
    {
        $this->registry = $registry;
        $this->escaper = $escaper;
    }

    public function afterRenderTitle(\Magento\Framework\View\Page\Config\Renderer $subject, $result)
    {
        $brand = $this->registry->registry('current_brand');
        if (!$brand instanceof \MageSuite\BrandManagement\Api\Data\BrandsInterface) {
            return $result;
        }
        $metaTitle = $brand->getMetaTitle();
        if (empty($metaTitle)) {
            return $result;
        }
        return '<title>' . $this->escaper->escapeHtml($metaTitle) . '</title>' . "\n";
    }
}