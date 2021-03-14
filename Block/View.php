<?php

namespace MageSuite\BrandManagement\Block;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return \MageSuite\BrandManagement\Model\Brands
     */
    public function getBrand()
    {
        return $this->registry->registry('current_brand');
    }
}
