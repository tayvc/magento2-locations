<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

use Magento\Backend\Model\View\Result\Page;

class Edit extends Stores
{
    /**
     * Edit a locations details
     * @return void
     */
    public function execute()
    {
        $storesId = (int) $this->getRequest()->getParam('id');
        $storesModel = $this->_storeFactory->create();

        if ($storesId) {
            $storesModel->load($storesId);
            if (!$storesModel->getId()) {
                $this->messageManager->addError(__('This store no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // Restore previously entered form data from session
        $this->_session->getStoresData(true);

        if (!empty($data)) {
            $storesModel->setData($data);
        }

        $this->_coreRegistry->register('locations', $storesModel);

        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('SFS_Locations::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Locations'));

        return $resultPage;
    }
}
