<?php

namespace App\Actions\ItemRequests;

use App\Models\Item;

class SetupItemData
{
    /**
     * Setup ItemRequestDetail Data
     *
     * @param array $data Required keys 'sku', 'quantity'
     * @return array
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function __invoke(array $data): array
    {
        $item = Item::where('sku', $data['sku'])->where('is_active', true)->firstOrFail();

        $data['unit_of_measure'] = $item->unit_of_measure;
        $data['unit_cost'] = $item->unit_cost;
        $data['total_cost'] = (float) $data['unit_cost'] * (float) $data['quantity'];

        return $data;
    }
}
