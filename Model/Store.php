<?php
namespace SFS\Locations\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/***
 * Class Store
 *
 * @package RefactorTest\Locations\Model
 */
class Store extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'stores_locations';

    protected $_cacheTag = 'stores_locations';

    protected $_eventPrefix = 'stores_locations';

    /***
     * Create a new store resource object
     */
    protected function _construct()
    {
        $this->_init('SFS\Locations\Model\ResourceModel\Store');
    }

    /***
     * This returns the columns for the model.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /***
     * This returns the values for the model.
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    /***
     * Returns the store's address fields as an array.
     *
     * @return array
     */
    public function getAddressAsArray()
    {
        return [
            'street' => $this->getStreetAddress(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
        ];
    }
}
