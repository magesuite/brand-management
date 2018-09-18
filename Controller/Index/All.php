<?php

namespace MageSuite\BrandManagement\Controller\Index;

class All extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);

    }

    public function execute()
    {
        $result = $this->pageFactory->create();

        return $result;
    }
}