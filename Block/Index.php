<?php
namespace SFS\Locations\Block;

use Magento\Catalog\Helper\ImageFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SFS\Locations\Helper\Data as HelperData;
use SFS\Locations\Helper\GoogleMaps;

/***
 * Class Index
 *
 * @package SFS\Locations\Block
 */
class Index extends Template
{
    protected $_helperData;
    protected $_googleMaps;
    protected $_imageFactory;
    protected $_locations;

    /***
     * Index constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param GoogleMaps $googleMaps
     * @param ImageFactory $imageFactory
     */
    public function __construct(Context $context, HelperData $helperData, GoogleMaps $googleMaps, ImageFactory $imageFactory)
    {
        $this->_helperData = $helperData;
        $this->_googleMaps = $googleMaps;

        // Set the image factory for getting default images.
        $this->_imageFactory = $imageFactory;

        // Set the locations collection
        $this->_locations = $this->getLocationCollection();

        parent::__construct($context);
    }

    /***
     * This method is for returning the data collection.
     *
     * @return mixed
     */
    public function getLocationCollection()
    {
        if (!isset($this->_locations)) {
            $this->_locations = $this->_helperData->getLocationsCollection();
        }

        return $this->_locations;

    }

    /***
     * This method returns a given store location's hours as an array.
     * @param $store SoftwareForSapiens\Locations\Model\Store
     * @return array
     */
    public function getStoreHoursAsArray($store)
    {
        $storeHours = [];
        // If the given store has set store hours, decode it as an array
        if ($store->getStoreHours()) {
            $storeHours = json_decode($store->getStoreHours(), true);
        }
        return $storeHours;
    }

    /***
     * Method to return the URL for a location's Google Map image if enabled.
     *
     * @param $store
     * @return string
     */
    public function getGoogleMapsImage($store)
    {
        $image = false;
        if(isset($store)) {
            $image = $this->_googleMaps->getGoogleMapsImageUrl($store);
        }
        return $image;
    }

    /***
     * Method to return the current week starting from Sunday in m/d format
     *
     * @return string
     */
    public function getCurrentWeek()
    {
        $monday = strtotime('last monday');
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date('m/d', $monday) . '+6 days');

        $currentWeekStart = date('m/d', $monday);
        $currentWeekEnd = date('m/d', $sunday);

        return $currentWeekStart . ' - ' . $currentWeekEnd;
    }

    /***
     * Method to return the store location's image URL.
     * If no image is set, default placeholder will be used instead.
     * @param $store SoftwareForSapiens\Locations\Model\Store
     * @return string
     */
    public function getStoreImageUrl($store)
    {
        if ($store->getImageUrl()) {
            $url = $store->getImageUrl();
        } else {
            $image = $this->_imageFactory->create();
            $url = $image->getDefaultPlaceholderUrl('image');
        }
        return $url;
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

    /**
     * Method returns a multi-dimensional array of locations
     * based on their relative state and its first alphabetic character.
     * @param $locations The collection of locations
     * @return array
     */
    public function getRegionAlphaArray($locations)
    {
        $regions = [];

        // Check that locations exist and there is at least one
        if ($locations && $locations->getSize() > 0) {
            foreach ($locations as $location) {
                // Check that this location has a state
                if ($location->getState()) {
                    $state = $location->getState();
                    // Check that the state is a string value
                    if (is_string($state)) {
                        $letter = strtoupper(substr($state, 0, 1));
                        $regions[$letter][$state][] = $location;
                    }
                }
            }
        }

        return $regions;
    }
}
