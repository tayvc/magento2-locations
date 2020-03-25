<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use SFS\Locations\Model\StoreFactory;

class Save extends Stores
{
    // Create event manager to dispatch after successful save
    protected $_eventManager;

    public function __construct(Context $context, Registry $coreRegistry, PageFactory $resultPageFactory, StoreFactory $storeFactory, ManagerInterface $eventManager)
    {
        $this->_eventManager = $eventManager;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $storeFactory);
    }

    /**
     * Save the new location
     * @return void
     */
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();

        if ($isPost) {
            $storesModel = $this->_storeFactory->create();

            $storeId = (int) $this->getRequest()->getParam('id');

            if ($storeId) {
                $storesModel->load($storeId);
            }
            $formData = $this->getRequest()->getParam('stores');
            $storesModel->setData($formData);

            try {
                // Save store
                $storesModel->save();

                // Dispatch event to generate store's coordinates
                $this->_eventManager->dispatch('store_locations_save_after', ['store' => $storesModel]);

                // Display success message
                $this->messageManager->addSuccess(__('The store has been saved.'));

                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $storesModel->getId(), '_current' => true]);
                    return;
                }

                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $storeId]);
        }
    }
}
