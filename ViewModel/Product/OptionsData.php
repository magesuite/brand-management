<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\ViewModel\Product;

class OptionsData implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $viewModel;

    public function getOptionsData(\Magento\Catalog\Model\Product $product) : array
    {
        if (!$this->getViewModel() instanceof \Magento\Framework\View\Element\Block\ArgumentInterface) {
            return [];
        }

        return $this->getViewModel()->getOptionsData($product);
    }

    protected function getViewModel()
    {
        if (!$this->viewModel && class_exists('Magento\Catalog\ViewModel\Product\OptionsData')) {
            $this->viewModel = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Catalog\ViewModel\Product\OptionsData');
        }

        return $this->viewModel;
    }
}
