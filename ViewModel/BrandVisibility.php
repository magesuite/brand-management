<?php
namespace MageSuite\BrandManagement\ViewModel;

class BrandVisibility implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isVisible($location)
    {
        return $this->configuration->isVisible($location);
    }
}
