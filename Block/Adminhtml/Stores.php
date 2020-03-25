<?php
namespace SFS\Locations\Block\Adminhtml;

class Stores extends \Magento\Backend\Block\Widget\Grid\Container
{
    /*
     * Construct a store block.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_stores';
        $this->_blockGroup = 'SFS_Locations';
        $this->_headerText = __('Locations');
        $this->_addButtonLabel = __('Create A New Location');
        parent::_construct();
    }
}
