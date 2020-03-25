<?php

namespace SFS\Locations\Block\Adminhtml\Stores\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
use Magento\Framework\Exception\LocalizedException;

class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('stores_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Locations Information'));
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'store_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'SFS\Locations\Block\Adminhtml\Stores\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}
