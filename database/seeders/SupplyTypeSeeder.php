<?php

namespace Database\Seeders;

use App\Models\SupplyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplyTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Common Supply',
            'Non-Common Supply',
            'Inventory Item',
            'Services',
        ];

        foreach ($data as $record) {
            SupplyType::withoutEvents(function () use ($record) {
                SupplyType::firstOrCreate(['name' => $record]);
            });
        }
    }
}
