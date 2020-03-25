<?php
namespace SFS\Locations\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Controller\Result\JsonFactory;
use SFS\Locations\Helper\GoogleMaps;

/***
 * Class Search
 *
 * @package SFS\Locations\Controller\Index
 */
class Search extends Action
{
    protected $googleMaps;
    protected $jsonHelper;
    protected $jsonFactory;

    /**
     * Search constructor.
     *
     * @param Context $context
     * @param JsonHelper $jsonHelper
     * @param JsonFactory $jsonFactory
     * @param GoogleMaps $googleMaps
     */
    public function __construct(
        Context $context,
        JsonHelper $jsonHelper,
        JsonFactory $jsonFactory,
        GoogleMaps $googleMaps
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->jsonFactory = $jsonFactory;
        $this->googleMaps = $googleMaps;
        parent::__construct($context);
    }


    public function execute()
    {
        $address = $this->getRequest()->getParam('address');

        if ($this->getRequest()->isAjax()) {
            // Get closest store locations to address searched
            $stores = $this->googleMaps->getStoreLocator($address);

            $html = $this->_view->getLayout()->createBlock('SFS\Locations\Block\Locator')->setData('stores', $stores)->toHtml();
            $resultJson = $this->jsonFactory->create();

            return $resultJson->setData(['html' => $html]);
        }
    }
}
