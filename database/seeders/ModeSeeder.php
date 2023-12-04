<?php

namespace Database\Seeders;

use App\Models\Mode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "Competitive Public Bidding",
            "Direct Contracting",
            "Repeat Order",
            "Shopping",
            "Negotiated Procurement | Two Failed Biddings",
            "Negotiated Procurement | Emergency Procurement",
            "Negotiated Procurement | Take-Over of Contracts",
            "Negotiated Procurement | Adjacent or Contiguous",
            "Negotiated Procurement | Agency to Agency"
        ];

        foreach ($data as $record) {
            Mode::withoutEvents(function () use ($record) {
                    Mode::firstOrCreate(['name' => $record]);
            });
        }
    }
}
