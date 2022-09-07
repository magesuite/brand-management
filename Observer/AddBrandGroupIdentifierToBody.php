<?php

namespace MageSuite\BrandManagement\Observer;

class AddBrandGroupIdentifierToBody implements \Magento\Framework\Event\ObserverInterface
{
    const BRAND_PAGE_DEFAULT_BODY_CLASS = 'brands-index-index';

    protected \Magento\Framework\Registry $registry;

    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $response = $observer->getEvent()->getResponse();

        if (!$response) {
            return;
        }

        $html = $response->getBody();

        if (empty($html)) {
            return;
        }

        $updatedHtml = $this->addBrandGroupIdentifierToBody($html);

        if (!$updatedHtml) {
            return;
        }

        $response->setBody($updatedHtml);
    }

    public function addBrandGroupIdentifierToBody($html)
    {
        $currentBrand = $this->registry->registry(\MageSuite\BrandManagement\Controller\Index\Index::CURRENT_BRAND);

        if (!$currentBrand) {
            return null;
        }

        $brandGroupIdentifier = $currentBrand->getBrandGroupIdentifier();

        if (!$brandGroupIdentifier
            || strpos($html, '<body') === false
            || strpos($html, self::BRAND_PAGE_DEFAULT_BODY_CLASS) === false) {
            return null;
        }

        $updatedClass = sprintf(
            '%s %s',
            self::BRAND_PAGE_DEFAULT_BODY_CLASS,
            $brandGroupIdentifier
        );

        return str_replace(self::BRAND_PAGE_DEFAULT_BODY_CLASS, $updatedClass, $html);
    }
}
