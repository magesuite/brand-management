<?php

namespace MageSuite\BrandManagement\Controller\Index;

class All extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \MageSuite\BrandManagement\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Escaper $escaper,
        \MageSuite\BrandManagement\Helper\Configuration $configuration
    )
    {
        $this->pageFactory = $pageFactory;
        $this->escaper = $escaper;
        $this->configuration = $configuration;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->pageFactory->create();
        $this->getSeoMetaTagForTitle($result);
        $this->getSeoMetaTagForDescription($result);
        $this->getSeoMetaTagForRobots($result);
        return $result;
    }

    protected function getSeoMetaTagForTitle($result)
    {
        if ($metaTitle = $this->getSeoMetaTagFor('title')) {
            $result->getConfig()->getTitle()->set($metaTitle);
        }
    }

    protected function getSeoMetaTagForDescription($result)
    {
        if ($metaDescription = $this->getSeoMetaTagFor('description')) {
            $result->getConfig()->setDescription($metaDescription);
        }
    }

    protected function getSeoMetaTagForRobots($result)
    {
        if ($metaRobots = $this->getSeoMetaTagFor('robots')) {
            $result->getConfig()->setRobots($metaRobots);
        }
    }

    protected function getSeoMetaTagFor(string $metaTag): ?string
    {
        $value = trim($this->configuration->getSeoMetaTagFor($metaTag));
        return !empty($value) ? $this->escaper->escapeHtml($value) : null;
    }
}
