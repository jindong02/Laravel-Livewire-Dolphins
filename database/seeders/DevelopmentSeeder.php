<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('db:seed --class=ItemSeeder');
        Artisan::call('db:seed --class=ModeSeeder');
        Artisan::call('db:seed --class=DepartmentSeeder');
        Artisan::call('db:seed --class=FundSourceSeeder');
        Artisan::call('db:seed --class=SupplyTypeSeeder');
        Artisan::call('db:seed --class=PurchaseRequestStatusSeeder');
        Artisan::call('db:seed --class=MinuteTemplateSeeder');
        Artisan::call('db:seed --class=PlotItemSeeder');
        Artisan::call('db:seed --class=PlotModuleSeeder');
        Artisan::call('app:acl:sync');

        $dept = Department::first();
        $user = User::firstOrCreate(['email' => 'crazy.ey1997@gmail.com'],[
            "name" => "John Doe",
            "last_name" => "Doe",
            "first_name" => "John",
            "is_active" => true,
            "department_id" => $dept->id,
            "password" => Hash::make('Welcome@1'),
        ]);

        $user->assignRole(Role::USER);

        $dept2 = Department::where('id', '<>', $dept->id)->first();
        $user2 = User::firstOrCreate(['email' => 'demo@gmail.com'],[
            "name" => "John Doe",
            "last_name" => "Doe",
            "first_name" => "John",
            "is_active" => true,
            "department_id" => $dept2->id,
            "password" => Hash::make('Welcome@1'),
        ]);

        $user2->assignRole(Role::USER);
    }
}