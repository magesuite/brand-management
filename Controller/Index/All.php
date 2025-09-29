<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller\Index;

class All extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \Magento\Framework\Escaper $escaper,
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \Magento\Framework\App\Action\Context $context,
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page
    {
        $result = $this->pageFactory->create();
        $this->getSeoMetaTagForTitle($result);
        $this->getSeoMetaTagForDescription($result);
        $this->getSeoMetaTagForRobots($result);

        return $result;
    }

    protected function getSeoMetaTagForTitle(\Magento\Framework\View\Result\Page $result): void
    {
        $metaTitle = $this->getSeoMetaTagFor('title');

        if ($metaTitle) {
            $result->getConfig()->getTitle()->set($metaTitle);
        }
    }

    protected function getSeoMetaTagForDescription(\Magento\Framework\View\Result\Page $result): void
    {
        $metaDescription = $this->getSeoMetaTagFor('description');

        if ($metaDescription) {
            $result->getConfig()->setDescription($metaDescription);
        }
    }

    protected function getSeoMetaTagForRobots(\Magento\Framework\View\Result\Page $result): void
    {
        $metaRobots = $this->getSeoMetaTagFor('robots');

        if ($metaRobots) {
            $result->getConfig()->setRobots($metaRobots);
        }
    }

    protected function getSeoMetaTagFor(string $metaTag): ?string
    {
        $valueFromConfiguration = $this->configuration->getSeoMetaTagFor($metaTag);
        $value = $valueFromConfiguration === null ? null : trim($this->configuration->getSeoMetaTagFor($metaTag));

        return !empty($value) ? $this->escaper->escapeHtml($value) : null;
    }
}
