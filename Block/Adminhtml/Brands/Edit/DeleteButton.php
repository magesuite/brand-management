<?php

namespace MageSuite\BrandManagement\Block\Adminhtml\Brands\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Cms\Block\Adminhtml\Block\Edit\GenericButton;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getBrandId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf("deleteConfirm('%s', '%s')", __('Are you sure you want to do this?'), $this->getDeleteUrl()),
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/brand/delete', ['id' => $this->getBrandId()]);
    }

    /**
     * Return Brand Id
     *
     * @return int|null
     */
    public function getBrandId()
    {
        $params = $this->context->getRequest()->getParams();
        if(!isset($params['id'])){
            return null;
        }

        return $params['id'];
    }
}
