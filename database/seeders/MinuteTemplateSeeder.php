<?php

namespace Database\Seeders;

use App\Models\MinuteTemplate;
use App\Models\PurchaseRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinuteTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = PurchaseRequestStatus::all();

        foreach ($statuses as $status) {
            $data = [];
            switch ($status->code) {
                /**
                 * Pre-Procurement Review
                 */
                case '10':
                    $data['complete_document'] = 'boolean';
                    $data['approved_pr'] = 'boolean';
                    $data['complete_terms_of_reference'] = 'boolean';
                    $data['market_research'] = 'boolean';
                    break;
                /**
                 * Pre-Procurement
                 */
                case '20':
                    $data['technical_specification'] = 'boolean';
                    $data['pre_bid_document'] = 'boolean';
                    $data['mom_recorded_posted'] = 'boolean';
                    $data['compete_tor'] = 'boolean';
                    $data['compete_sow'] = 'boolean';
                    break;
                /**
                 * Finalize Bidding Document and Activites
                 */
                case '30':
                    $data['posting_to_philgeps'] = 'boolean';
                    break;
                /**
                 * Pre-Bid Conference
                 */
                case '40':
                    $data['pre_bid_attachment'] = 'file';
                    break;
                /**
                 * Submission and Openning of Bid/s
                 */
                case '50':
                    $data['openning_bid_attachment'] = 'file';
                    break;
                /**
                 * Bid Evaluation
                 */
                case '60':
                    break;
                /**
                 * Post Qualification Requirements
                 */
                case '70':
                    $data['email_sent'] = 'boolean';
                    break;
                /**
                 * Post Qualification
                 */
                case '80':
                    $data['pass_evaluation'] = 'boolean';
                    break;
                /**
                 * Technical Working Group Summary Report
                 */
                case '90':
                    $data['twg_evaluation'] = 'file';
                    $data['recommended_for_award'] = 'boolean';
                    break;
                /**
                 * Bid and Award Committee (BAC) Resolution
                 */
                case '100':
                    $data['bac_resolution'] = 'file';
                    $data['posted_to_philgeps'] = 'boolean';
                    break;
                /**
                 * Notice of Award (NOA)
                 */
                case '110':
                    $data['posted_to_philgeps_nkti_website'] = 'boolean';
                    break;
                /**
                 * NOA Issuance & Post Performance Bond
                 */
                case '120':
                    $data['performance_bond'] = 'file';
                    $data['send_noa_to_bidders'] = 'boolean';
                    $data['post_performance_bond'] = 'boolean';
                    break;
                /**
                 * Purchasing Division - For Goods
                 */
                case '130':
                    break;
                /**
                 * Business Records Management Unit - For Infra/Services
                 */
                case '140':
                    $data['contract'] = 'file';
                    break;
                /**
                 * Issuance of Notice to Proceed
                 */
                case '150':
                    $data['notice_to_proceed'] = 'file';
                    break;
                default:
                    # code...
                    break;
            }

            $this->createTemplate($status->code, $data);
        }
    }

    /**
     * Create Minute Template
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231103 - Created
     */
    public function createTemplate($status, $templates)
    {
        $order = 0;
        $labels = $this->labels();

        foreach ($templates as $key => $dataType) {
            MinuteTemplate::create([
                'status' => $status,
                'key' => $key,
                'label' => $labels[$key] ?? '',
                'data_type' => $dataType,
                'order' => $order,
                'is_active' => true,
            ]);

            $order++;
        }
    }

    public function labels()
    {
        return [
            'complete_document' => 'Check for the completeness of documents',
            'approved_pr' => 'Approved and Signed PR',
            'complete_terms_of_reference' => 'Complete Terms of References',
            'market_research' => 'Market Research for at least 3 Suppliers',
            'technical_specification' => 'Verify Technical Specification',
            'pre_bid_document' => 'Complete Pre-Bid Document',
            'mom_recorded_posted' => 'MOM Recorded and Posted',
            'compete_tor' => 'Complete TOR',
            'compete_sow' => 'Complete SOW',
            'posting_to_philgeps' => 'Posting of Bidding Documents to PhilGEPS',
            'attachment' => 'Attach File',
            'pre_bid_attachment' => 'Attach File',
            'openning_bid_attachment' => 'Attach File',
            'email_sent' => 'Sent Email to Bidders',
            'pass_evaluation' => 'Pass Evaluation',
            'twg_evaluation' => 'Attach File (TWG Evaluation)',
            'recommended_for_award' => 'Recommended for Award',
            'bac_resolution' => 'Attach File (BAC Resolution)',
            'posted_to_philgeps' => 'Posted to PhilGEPS',
            'posted_to_philgeps_nkti_website' => 'Posted to PhilGEPS and NKTI Website',
            'performance_bond' => 'Attach File (Performance Bond)',
            'send_noa_to_bidders' => 'Send copy of NOA to winning Bidder',
            'post_performance_bond' => 'Post Performance Bond',
            'contract' => 'Attach File (Contract)',
            'notice_to_proceed' => 'Attach File (Notice to Proceed)',
        ];
    }

}
