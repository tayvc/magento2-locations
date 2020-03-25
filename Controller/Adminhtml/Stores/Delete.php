<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

class Delete extends Stores
{
    /**
     * Delete a location
     * @return void
     */
    public function execute()
    {
        $storesId = (int) $this->getRequest()->getParam('id');

        if ($storesId) {
            $storesModel = $this->_storeFactory->create();
            $storesModel->load($storesId);

            // Check this store exists or not
            if (!$storesModel->getId()) {
                $this->messageManager->addError(__('This store no longer exists.'));
            } else {
                try {
                    // Delete store
                    $storesModel->delete();
                    $this->messageManager->addSuccess(__('The store has been deleted.'));

                    // Redirect to grid page
                    $this->_redirect('*/*/');
                    return;
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect('*/*/edit', ['id' => $storesModel->getId()]);
                }
            }
        }
    }
}
