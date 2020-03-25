<?php
namespace SFS\Locations\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/***
 * Class Store
 *
 * @package SFS\Locations\Model\ResourceModel
 */
class Store extends AbstractDb
{
    /***
     * Store constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /***
     * The Constructor of the model
     * @returns null
     */
    protected function _construct()
    {
        // Connect the db table and the primary key.
        $this->_init('store_locations', 'store_id');
    }
}
