<?php

namespace Database\Seeders;

use App\Models\PurchaseRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequestStatusSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Pre-Procurement Review', 'description' => 'Pre-Procurement Review',],
            ['name' => 'Pre-Procurement', 'description' => 'Pre-Procurement',],
            ['name' => 'Finalize Bidding Document and Activites', 'description' => 'Finalize Bidding Document and Schedule Bidding Activites',],
            ['name' => 'Pre-Bid Conference', 'description' => 'Pre-Bid Conference',],
            ['name' => 'Submission and Openning of Bid/s', 'description' => 'Submission and Openning of Bid/s',],
            ['name' => 'Bid Evaluation', 'description' => 'Bid Evaluation',],
            ['name' => 'Post Qualification Requirements', 'description' => 'Submission of Post Qualification Requirements and Identification of Lowest Calculated Responsive Bidder',],
            ['name' => 'Post Qualification', 'description' => 'Post Qualification',],
            ['name' => 'Technical Working Group Summary Report', 'description' => 'Technical Working Group Summary Report',],
            ['name' => 'Bid and Award Committee (BAC) Resolution', 'description' => 'Bid and Award Committee (BAC) Resolution',],
            ['name' => 'Notice of Award (NOA)', 'description' => 'Notice of Award (NOA)',],
            ['name' => 'NOA Issuance & Post Performance Bond', 'description' => 'Send Copy of NOA to winning Bidder and required to post Performance Bond ',],
            ['name' => 'Purchasing Division - For Goods ', 'description' => 'Purchasing Division - For Goods ',],
            ['name' => 'Business Records Management Unit - For Infra/Services', 'description' => 'Business Records Management Unit - For Infra/Services',],
            ['name' => 'Issuance of Notice to Proceed', 'description' => 'Issuance of Notice to Proceed',],
            ['name' => 'Completed', 'description' => 'Completed',],
        ];

        $code = 10;
        foreach ($data as $record) {
            PurchaseRequestStatus::withoutEvents(function () use ($record, $code) {
                PurchaseRequestStatus::firstOrCreate([
                    'name' => $record['name'],
                ],[
                    'description' => $record['description'],
                    'order' => $code,
                    'code' => $code,
                ]);
            });
            $code += 10;
        }
    }
}
