<?php
namespace SFS\Locations\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use SFS\Locations\Helper\GoogleMaps;

/**
 * Get Location Coordinates Observer
 * This is executed on the dispatched event of
 * a store location being updated in admin.
 *
 * @category    SFS
 * @package     SFS_Locations
 * @author      Taylor Van Cleave <taylor@softwareforsapiens.com>
 */

class GetLocationCoordinates implements ObserverInterface
{
    private $logger;
    private $googleMaps;

    /**
     * GetLocationCoordinates constructor.
     * @param LoggerInterface $logger
     * @param GoogleMaps $googleMaps
     */
    public function __construct(LoggerInterface $logger, GoogleMaps $googleMaps)
    {
        $this->logger = $logger;
        $this->googleMaps = $googleMaps;
    }

    /**
     * Generate the latitude and longitude coordinates
     * from the updated store location's address using
     * Google Maps API.
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        // Get the store model from the observer data
        $store = $observer->getData('store');

        try {

            // Check that data includes a valid store
            if ($store->getId()) {
                // Get store's address as comma separated string
                $address = implode(",", $store->getAddressAsArray());

                // Get geo coordinates from Google Maps
                $geo = $this->googleMaps->getGeoCoordinates($address);

                if (isset($geo['latitude']) && isset($geo['longitude'])) {
                    // Save coordinates to store model
                    $store->setLatitude($geo['latitude']);
                    $store->setLongitude($geo['longitude']);
                    $store->save();
                }

            } else {
                throw new \Exception('Unable to get location coordinates because store model is not valid.');
            }

        } catch (\Exception $e) {
            $this->logger->critical('Error message', ['exception' => $e]);
        }
    }

}
