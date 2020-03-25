<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

use SFS\Locations\Controller\Adminhtml\Stores\Index;

class NewAction extends Stores
{
    /**
     * Create new stores action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
