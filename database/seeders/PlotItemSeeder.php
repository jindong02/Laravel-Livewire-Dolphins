<?php

namespace Database\Seeders;

use App\Models\PlotItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlotItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            "mouse",
            "keyboard",
            "computer",
            "display",
            "phone",

        ];

        foreach ($data as $record) {
            PlotItem::withoutEvents(function () use ($record) {
                PlotItem::firstOrCreate(['name' => $record]);
            });
        }
    }
}