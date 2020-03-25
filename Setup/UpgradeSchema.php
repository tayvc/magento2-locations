<?php

namespace SFS\Locations\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.4') < 0) {

            // Get module table
            $tableName = $setup->getTable('store_locations');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data to add
                $addColumns = [
                    'color' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'Store Color',
                        'after' => 'position'
                    ],
                    'is_active' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        'nullable' => false,
                        'default' => true,
                        'after' => 'email',
                        'comment' => 'Store Enabled'
                    ],
                    'latitude' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'length' => '10,8',
                        'nullable' => true,
                        'after' => 'zip_code',
                        'comment' => 'Latitude'
                    ],
                    'longitude' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'length' => '11,8',
                        'nullable' => true,
                        'after' => 'zip_code',
                        'comment' => 'Longitude'
                    ]
                ];

                // Declare data to remove
                $dropColumns = [
                  'store_manager',
                  'position'
                ];

                $connection = $setup->getConnection();
                // Add color and active columns
                foreach ($addColumns as $name => $definition) {
                    // Check that column doesn't already exist
                    if (!$connection->tableColumnExists($tableName, $name)) {
                        $connection->addColumn($tableName, $name, $definition);
                    }
                }

                foreach($dropColumns as $dropColumn) {
                    // Check that column still exists
                    if ($connection->tableColumnExists($tableName, $dropColumn)) {
                        $connection->dropColumn($tableName, $dropColumn);
                    }
                }
            }
        }

        $setup->endSetup();
    }
}
