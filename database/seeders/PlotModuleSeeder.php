<?php

namespace Database\Seeders;

use App\Models\PlotModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;


class PlotModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Generate and insert dates
    $start = Carbon::create(2020, 1, 1);
    $end = Carbon::create(2023, 11, 13);

    for ($date = $start; $date <= $end; $date->addDay()) {
        // Generate and insert random numbers for this date
        $randomQtySold = rand(40, 90);
        $randomQtyRemain = rand(200, 400);
        $randomItemId = rand(1, 5);

        PlotModule::create([
            'dates' => $date->format('Y-m-d'),
            'qty_sold' => $randomQtySold,
            'qty_remain' => $randomQtyRemain,
            'item_id' => $randomItemId,
        ]);
    }
    }
}