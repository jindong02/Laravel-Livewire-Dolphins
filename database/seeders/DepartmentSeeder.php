<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "Emergency Department",
            "Cardiology Department",
            "Radiology Department",
        ];

        foreach ($data as $record) {
            Department::withoutEvents(function () use ($record) {
                Department::firstOrCreate(['name' => $record]);
            });
        }
    }
}
