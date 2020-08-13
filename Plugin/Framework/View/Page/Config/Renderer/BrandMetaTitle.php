<?php
namespace MageSuite\BrandManagement\Plugin\Framework\View\Page\Config\Renderer;

/**
 * This plugin changes meta title for brand page views, if meta title is set in admin.
 * The reason for this is that Magento's \Magento\Framework\View\Page\Config::setMetaTitle method really changes
 * <meta name="title" content="..." /> (which is ignored by Google Robot) instead of <title>.
 * \Magento\Framework\View\Page\Config::getTitle()->set, cannot be used in Controller because this title is later used
 * in page.main.title blocks to output H1, and \MageSuite\ThemeHelpers\Block\Category\View\Headline::getHeadline,
 * and above two should not be affected by meta title set for brand in admin.
 */
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
