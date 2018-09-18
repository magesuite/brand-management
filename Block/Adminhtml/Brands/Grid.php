<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands;

class Grid extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_brands';
        $this->_blockGroup = 'MageSuite_BrandManagement';
        $this->_headerText = __('Posts');
        $this->_addButtonLabel = __('Create New Brand');
        parent::_construct();
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/brand/newbrand');
    }
}