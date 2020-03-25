<?php
namespace SFS\Locations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Google Maps Helper
 *
 * @category    SFS
 * @package     SFS_Locations
 * @author      Taylor Van Cleave <taylor@softwareforsapiens.com>
 */

class GoogleMaps extends AbstractHelper
{
    const XML_PATH_GOOGLE_MAPS = 'google_maps/';
    const GOOGLE_MAPS_STATIC_LINK = 'https://maps.googleapis.com/maps/api/staticmap';
    const GOOGLE_MAPS_GEOCODE_LINK = 'https://maps.googleapis.com/maps/api/geocode/json';

    private $curl;
    private $logger;
    protected $helper;


    /**
     * GoogleMaps constructor.
     * @param Context $context
     * @param Curl $curl
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(Context $context, Curl $curl, LoggerInterface $logger, Data $helper)
    {
        $this->curl = $curl;
        $this->logger = $logger;
        $this->helper = $helper;

        parent::__construct($context);
    }

    /***
     * Get system configuration value.
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /***
     * Get general configuration setting.
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GOOGLE_MAPS . 'general/' . $code, $storeId);
    }

    /***
     * Check if google maps is enabled with a set API key.
     * @return bool
     */
    public function isGoogleMapsEnabled()
    {
        // Check that Google Maps are enabled on this site and an API key is set.
        if ($this->getGeneralConfig('enable') && !empty($this->getGeneralConfig('api_key'))) {
            return true;
        }
        return false;
    }

    /***
     * Method to return the URL to a Google Maps image
     * for a specific store using the Google Maps API.
     * @param $store SFS\Locations\Model\Store
     * @return string
     */
    public function getGoogleMapsImageUrl($store)
    {
        $imageUrl = false;
        // Only return a link if Google Maps is enabled on this site
        if ($this->isGoogleMapsEnabled()) {
            // Build API
            $address = implode(",", $store->getAddressAsArray());
            $params = [
                'center' => $address,
                'zoom' => 15,
                'size' => '600x300',
                'maptype' => 'roadmap',
                'markers' => 'color:red|' . $address,
                'key' => $this->getGeneralConfig('api_key')
            ];

            $imageUrl = self::GOOGLE_MAPS_STATIC_LINK . '?' . http_build_query($params);
        }

        return $imageUrl;
    }

    /**
     * Method to get latitude and longitude coordinates via
     * Google Maps API geocoding from an address or zip code.
     * @param $address
     * @return array|bool
     */
    public function getGeoCoordinates($address)
    {
        $result = false;

        try {

            // Check if address was provided
            if (isset($address) && !empty($address)) {

                if ($this->isGoogleMapsEnabled()) {
                    // Set query parameter for request
                    $query = [
                        'address' => $address,
                        'key' => $this->getGeneralConfig('api_key')
                    ];

                    // Create curl GET request to Google Maps API
                    $url = self::GOOGLE_MAPS_GEOCODE_LINK . '?' . http_build_query($query);
                    $this->curl->get($url);

                    // Make sure request status is 200
                    if ($this->curl->getStatus() == 200) {
                        // Get response data
                        $response = $this->curl->getBody();
                        $geo = json_decode($response, true);

                        // Check that response has OK status
                        if (isset($geo['status']) && $geo['status'] == 'OK') {
                            // Get latitude and longitude coordinates for store location
                            $latitude = $geo['results'][0]['geometry']['location']['lat'];
                            $longitude = $geo['results'][0]['geometry']['location']['lng'];

                            $result = [
                                'latitude' => $latitude,
                                'longitude' => $longitude
                            ];

                        } else {
                            throw new \Exception('Invalid response from Google Maps API.');
                        }

                    } else {
                        throw new \Exception('Unable to make successful request to Google Maps API.');
                    }

                } else {
                    throw new \Exception('Google Maps is disabled or there is no valid API key defined.');
                }

            } else {
                throw new \Exception('Unable to lookup coordinates because no valid address was provided.');
            }

        } catch (\Exception $e) {
            $this->logger->critical('Error message', ['exception' => $e]);
        }

        return $result;
    }

    /**
     * Method to get store locations within 100 miles of given address.
     * @param $address
     * @return bool|AbstractCollection
     */
    public function getStoreLocator($address)
    {
        // Get coordinates for the provided address
        $coordinates = $this->getGeoCoordinates($address);

        // Check that coordinates were successfully found
        if ($coordinates) {
            $latitude = $coordinates['latitude'];
            $longitude = $coordinates['longitude'];

            // Get active locations collection
            $collection = $this->helper->getLocationsCollection();
            // Only select the fields we need to display for result listing
            $collection->addFieldToSelect(['name', 'street_address', 'city', 'state', 'zip_code', 'phone_number']);
            // Select locations that are within 100 miles of the provided address
            $collection->addExpressionFieldToSelect(
                'distance', '( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( {{latitude}} ) ) * cos( radians( {{longitude}} ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( {{latitude}} ) ) ) )',
                array('latitude' => 'latitude', 'longitude' => 'longitude')
            );
            $collection->getSelect()->having('distance < 100');
            // Order locations by closest distance
            $collection->setOrder('distance', 'ASC');

            return $collection;

        } else {
            return false;
        }
    }
}
