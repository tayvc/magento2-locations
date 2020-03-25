<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

use SFS\Locations\Model\Store;

class MassDelete extends Stores
{
    /**
     * Delete multiple locations
     * @return void
     */
    public function execute()
    {
        // Get IDs of the selected stores
        $storesIds = $this->getRequest()->getParam('stores');

        foreach ($storesIds as $storesId) {
            try {
                $storesModel = $this->_storeFactory->create();
                $storesModel->load($storesId)->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        if (count($storesIds)) {
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) were deleted.', count($storesIds))
            );
        }

        $this->_redirect('*/*/index');
    }
}
