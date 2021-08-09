<?php
namespace MageSuite\BrandManagement\Plugin\Framework\View\Page\Config\Renderer;

/**
 * This plugin changes meta title for brand page views, if meta title is set in admin.
 * The reason for this is that Magento's \Magento\Framework\View\Page\Config::setMetaTitle method really changes
 * <meta name="title" content="..." /> (which is ignored by Google Robot) instead of <title></title>.
 * \Magento\Framework\View\Page\Config::getTitle()->set(), cannot be used in Controller because this title is later used
 * in page.main.title blocks to output H1, and \MageSuite\ThemeHelpers\Block\Category\View\Headline::getHeadline,
 * and above two should not be affected by meta title set for brand in admin. This plugin also changes <title></title>
 * tag for brands overview page, if 'Stores / Configuration / MageSuite / Brands / Search Engine Optimization
 * for brands overview page' has set 'Meta title'.
 */
class AddBrandMetaTitle
{
    public const TITLE_FORMAT = '<title>%s</title>' . PHP_EOL;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\App\Request\Http $request,
        \MageSuite\BrandManagement\Helper\Configuration $configuration
    )
    {
        $this->registry = $registry;
        $this->escaper = $escaper;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function afterRenderTitle(\Magento\Framework\View\Page\Config\Renderer $subject, $result)
    {
        if ($this->isItBrandsOverviewPage()) {
            $metaTitle = trim($this->escaper->escapeHtml($this->configuration->getSeoMetaTagFor('title')));
        }
        if (!empty($metaTitle)) {
            return sprintf(self::TITLE_FORMAT, $metaTitle);
        }

        $brand = $this->registry->registry('current_brand');
        if (!$brand instanceof \MageSuite\BrandManagement\Api\Data\BrandsInterface) {
            return $result;
        }

        $metaTitle = $brand->getMetaTitle();
        if (empty($metaTitle)) {
            return $result;
        }
        return sprintf(
            self::TITLE_FORMAT,
            trim($this->escaper->escapeHtml($metaTitle))
        );
    }

    protected function isItBrandsOverviewPage(): bool
    {
        return $this->request->getFullActionName() === 'brands_index_all';
    }
}
