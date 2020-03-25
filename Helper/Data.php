<?php

namespace SFS\Locations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SFS\Locations\Model\StoreFactory;

class Data extends AbstractHelper
{
    // Create a property to store the factory for the db table.
    protected $_storeFactory;

    public function __construct(Context $context, StoreFactory $storeFactory)
    {
        // Create a factory for creating classes for the store object.
        $this->_storeFactory = $storeFactory;
        parent::__construct($context);
    }

    /**
     * Method to return active store locations collection
     * @return AbstractCollection
     */
    public function getLocationsCollection()
    {
        $storeFactory = $this->_storeFactory->create();
        $collection = $storeFactory->getCollection();

        // Only return store locations that are currently active
        $collection->addFieldToFilter('is_active', true);
        // Order locations by alphabetical state
        $collection->setOrder('state', 'ASC');

        return $collection;
    }

    /***
     * The Method is for formatting phone number from an int or string with no dashes or parentheses.
     * It also can add the country code as well.
     * To Adjust the formatting you can change the replacement tag value in the preg_replace method.
     * @param $phone The string or int of the phone number you want to format.
     * @return string|string[]|null
     */
    public function formatStorePhoneNumber($phone)
    {
        $formatted = false;
        // note: making sure we have something
        if (isset($phone{3})) {
            // note: strip out everything but numbers
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            switch ($length) {
                case 7:
                    $formatted = preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                    break;
                case 9:
                    $formatted = preg_replace("/([0-9]{3})([0-9]{2})([0-9]{4})/", "($1) $2-$3", $phone);
                    break;
                case 10:
                    $formatted = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                    break;
                case 11:
                    $formatted = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
                    break;
            }
        }
        return $formatted;
    }
}
