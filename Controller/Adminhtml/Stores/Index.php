<?php

namespace SFS\Locations\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/***
 * Class Index
 *
 * @package SFS\Locations\Controller\Adminhtml\Stores
 */
class Index extends Action
{
    protected $resultPageFactory = false;

    /***
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /***
     * This will be called when the http request is made to the module for the front-end.
     *
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Locations')));

        return $resultPage;
    }
}
