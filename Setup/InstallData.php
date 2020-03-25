<?php

namespace SFS\Locations\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $_storeFactory;

    /***
     * InstallData constructor.
     * @param \SFS\Locations\Model\StoreFactory $storeFactory
     */
    public function __construct(\SFS\Locations\Model\StoreFactory $storeFactory)
    {
        $this->_storeFactory = $storeFactory;
    }

    /***
     * Create sample data in the database.
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Install sample stores for testing.
         */
        $data = [
            'name' => 'Grants Pass',
            'street_address' => '123 Main Street',
            'city' => 'Grants Pass',
            'state' => 'Oregon',
            'zip_code' => 12345,
            'phone_number' => '1234567890',
            'email' => 'roguevalleystore@email.com',
            'image_url' => 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1655&q=80',
            'facebook_url' => 'https://facebook.com/store',
            'instagram_url' => 'https://instagram.com/store',
            'pinterest_url' => 'https://pinterest.com/store',
            'twitter_url' => 'https://twitter.com/store',
            'store_hours' => '{"Monday":{"Start":"10:00AM","Stop":"5:00PM"},"Tuesday":{"Start":"10:00AM","Stop":"5:00PM"},"Wednesday":{"Start":"10:00AM","Stop":"5:00PM"},"Thursday":{"Start":"10:00AM","Stop":"5:00PM"},"Friday":{"Start":"10:00AM","Stop":"5:00PM"},"Saturday":{"Start":"12:00PM","Stop":"5:00PM"},"Sunday":null}'
        ];

        $data2 = [
            'name' => 'San Francisco',
            'street_address' => '456 Main Street',
            'city' => 'San Francisco',
            'state' => 'California',
            'zip_code' => 45678,
            'phone_number' => '4567891230',
            'email' => 'bayareastore@email.com',
            'image_url' => 'https://images.unsplash.com/photo-1464869372688-a93d806be852?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2550&q=80',
            'facebook_url' => 'https://facebook.com/store2',
            'instagram_url' => 'https://instagram.com/store2',
            'pinterest_url' => 'https://pinterest.com/store2',
            'twitter_url' => 'https://twitter.com/store2',
            'store_hours' => '{"Monday":{"Start":"8:00AM","Stop":"5:00PM"},"Tuesday":{"Start":"8:00AM","Stop":"5:00PM"},"Wednesday":null,"Thursday":{"Start":"8:00AM","Stop":"5:00PM"},"Friday":{"Start":"8:00AM","Stop":"5:00PM"},"Saturday":null,"Sunday":null}'
        ];

        $store = $this->_storeFactory->create();
        $store->addData($data)->save();
        $store2 = $this->_storeFactory->create();
        $store2->addData($data2)->save();
    }
}
