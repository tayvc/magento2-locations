<?php

namespace SFS\Locations\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * This is the active status of a store location.
 * If a store is active, it will display as enabled in the interface.
 * If a store is not active, it will display as disabled in the interface.
 */
class Status implements OptionSourceInterface
{

    const STORE_ENABLED = 1;
    const STORE_DISABLED = 0;

    public static function getOptionArray()
    {
        return [
            self::STORE_ENABLED => __('Enabled'),
            self::STORE_DISABLED => __('Disabled')
        ];
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $res = [];
        foreach (self::getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }
}
