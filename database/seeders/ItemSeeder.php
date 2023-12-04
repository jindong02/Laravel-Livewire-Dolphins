<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $items = [
            [
                "name" => "Digital Thermometer",
                "description" => "A digital thermometer for measuring body temperature.",
                "unit_of_measure" => "Piece",
                "unit_cost" => 19.99,
                "ipsas_code" => '1',
            ],
            [
                "name" => "Disposable Face Masks",
                "description" => "A box of 50 disposable face masks for protection.",
                "unit_of_measure" => "Box",
                "unit_cost" => 8.99,
                "ipsas_code" => '2',
            ],
            [
                "name" => "Surgical Gloves",
                "description" => "A box of 100 surgical gloves for medical procedures.",
                "unit_of_measure" => "Box",
                "unit_cost" => 14.50,
                "ipsas_code" => '3',
            ],
            [
                "name" => "Pulse Oximeter",
                "description" => "A device for measuring blood oxygen levels and pulse rate.",
                "unit_of_measure" => "Piece",
                "unit_cost" => 34.99,
                "ipsas_code" => '4',
            ],
            [
                "name" => "Medical Gowns",
                "description" => "A pack of 5 disposable medical gowns for healthcare workers.",
                "unit_of_measure" => "Pack",
                "unit_cost" => 45.00,
                "ipsas_code" => '5',
            ],
            [
                "name" => "Wheelchair",
                "description" => "A standard wheelchair for mobility assistance.",
                "unit_of_measure" => "Piece",
                "unit_cost" => 199.99,
                "ipsas_code" => '6',
            ]
        ];


        foreach ($items as $item) {
            Item::withoutEvents(function () use ($item) {
                Item::firstOrCreate(['sku' => Str::slug($item['name'])], $item);
            });
        }

    }
}
