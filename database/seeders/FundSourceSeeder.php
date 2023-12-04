<?php

namespace Database\Seeders;

use App\Models\FundSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSourceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "Regular Agency Fund (01000000)",
            "Foreign Assisted Projects Fund (02000000)",
            "Special Account - Locally Funded/Domestic Grants Fund (03000000)",
            "Special Account - Foreign Assisted/Foreign Grants Fund (04000000)",
            "Internally Generated Funds (05000000)",
            "Business Related Funds (06000000)",
            "Trust Receipts (07000000)",
        ];

        foreach ($data as $record) {
            FundSource::withoutEvents(function () use ($record) {
                FundSource::firstOrCreate(['name' => $record]);
            });
        }
    }
}
