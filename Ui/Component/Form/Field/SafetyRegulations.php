<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Ui\Component\Form\Field;

class SafetyRegulations extends \Magento\Ui\Component\Form\Field
{
    public function __construct(
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepare(): void
    {
        if ($this->configuration->isWysiwygForSafetyRegulationsEnabled()) {
            $this->_data['config']['formElement'] = 'wysiwyg';
            $this->_data['config']['dataType'] = 'text';
            $this->_data['config']['wysiwygConfigData'] = [
                'is_pagebuilder_enabled' => false,
               'toggle_button' => true,
               'height' => "200px",
               'add_variables' => true,
               'add_widgets' => true,
               'add_images' => true,
               'add_directives' => true
            ];
        }

        parent::prepare();
    }
}
