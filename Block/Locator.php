<?php

namespace SFS\Locations\Block;

use Magento\Framework\View\Element\Template;
use SFS\Locations\Helper\Data;

class Locator extends Template
{
    protected $_template = 'SFS_Locations::locator.phtml';
    protected $_helperData;

    public function __construct(Template\Context $context, array $data = [], Data $helperData)
    {
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }

    public function getStores()
    {
        return $this->getData('stores');
    }

    /***
     * The Method is for formatting phone number from an int or string with no dashes or parentheses.
     * It also can add the country code as well.
     * To Adjust the formatting you can change the replacement tag value in the preg_replace method.
     * @param $phone The string or int of the phone number you want to format.
     * @return string|string[]|null
     */
    public function formatPhoneNumber($phone)
    {
        return $this->_helperData->formatStorePhoneNumber($phone);
    }

}
