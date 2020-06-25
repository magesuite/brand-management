<?php
namespace MageSuite\BrandManagement\Model\Condition;

class IsBrandVisible implements \Magento\Framework\View\Layout\Condition\VisibilityConditionInterface
{
    const NAME = 'is_brand_visible';

    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\BrandManagement\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isVisible(array $arguments)
    {
        if (empty($arguments['context'])) {
            return false;
        }
        return $this->configuration->isVisible($arguments['context']);
    }

    public function getName()
    {
        return self::NAME;
    }
}
