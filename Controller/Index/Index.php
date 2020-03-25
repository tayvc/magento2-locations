<?php
namespace SFS\Locations\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/***
 * Class Index
 *
 * @package SFS\Locations\Controller\Index
 */
class Index extends Action
{
    protected $_pageFactory;

    /***
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /***
     * This will be called when the http request is made to the module for the front-end.
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->_pageFactory->create();
    }
}
