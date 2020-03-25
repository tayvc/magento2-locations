<?php
namespace SFS\Locations\Model\ResourceModel\Store;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/***
 * Class Collection
 *
 * @package SFS\Locations\Model\ResourceModel\Store
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'store_id';
    protected $_eventPrefix = 'store_locations_collection';
    protected $_eventObject = 'store_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SFS\Locations\Model\Store', 'SFS\Locations\Model\ResourceModel\Store');
    }
}
