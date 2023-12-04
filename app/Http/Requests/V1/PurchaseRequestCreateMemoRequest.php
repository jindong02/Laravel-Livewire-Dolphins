<?php

namespace App\Http\Requests\V1;

use App\Models\PurchaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequestCreateMemoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'purchase_request_id' => 'Purchase Request',
            'notes' => 'Meeting Minutes',
            'memo_date' => 'MOM Date',
            'memo_attachment' => 'Attachment for Minutes',
            'options' => 'Options',
            'options.complete_document' => 'Check for the completeness of documents',
            'options.approved_pr' => 'Approved and Signed PR',
            'options.complete_terms_of_reference' => 'Complete Terms of References',
            'options.market_research' => 'Market Research for at least 3 Suppliers',
            'options.technical_specification' => 'Verify Technical Specification',
            'options.pre_bid_document' => 'Complete Pre-Bid Document',
            'options.mom_recorded_posted' => 'MOM Recorded and Posted',
            'options.compete_tor' => 'Complete TOR',
            'options.compete_sow' => 'Complete SOW',
            'options.posting_to_philgeps' => 'Posting of Bidding Documents to PhilGEPS',
            'options.attachment' => 'Attach File',
            'options.email_sent' => 'Sent Email to Bidders',
            'options.pass_evaluation' => 'Pass Evaluation',
            'options.twg_evaluation' => 'Attach File (TWG Evaluation)',
            'options.recommended_for_award' => 'Recommended for Award',
            'options.bac_resolution' => 'Attach File (BAC Resolution)',
            'options.posted_to_philgeps' => 'Posted to PhilGEPS',
            'options.posted_to_philgeps_nkti_website' => 'Posted to PhilGEPS and NKTI Website',
            'options.performance_bond' => 'Attach File (Performance Bond)',
            'options.send_noa_to_bidders' => 'Send copy of NOA to winning Bidder',
            'options.post_performance_bond' => 'Post Performance Bond',
            'options.contract' => 'Attach File (Contract)',
            'options.notice_to_proceed' => 'Attach File (Notice to Proceed)',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $purchaseRequest = PurchaseRequest::findOrFail($this->input('purchase_request_id'));
        $rules = [
            'purchase_request_id' => ['required', Rule::exists('purchase_requests', 'id')],
            'notes' => ['nullable', 'string', 'max:400'],
            'memo_date' => ['required', 'date'],
            'memo_attachment' => ['sometimes', 'file', 'max:2000'],
            'options' => ['required', 'array'],
        ];

        switch ($purchaseRequest->status) {
            /**
             * Pre-Procurement Review
             */
            case '10':
                $rules['options.complete_document'] = ['required', 'boolean'];
                $rules['options.approved_pr'] = ['required', 'boolean'];
                $rules['options.complete_terms_of_reference'] = ['required', 'boolean'];
                $rules['options.market_research'] = ['required', 'boolean'];
                break;
            /**
             * Pre-Procurement
             */
            case '20':
                $rules['options.technical_specification'] = ['required', 'boolean'];
                $rules['options.pre_bid_document'] = ['required', 'boolean'];
                $rules['options.mom_recorded_posted'] = ['required', 'boolean'];
                $rules['options.compete_tor'] = ['required', 'boolean'];
                $rules['options.compete_sow'] = ['required', 'boolean'];
                break;
            /**
             * Finalize Bidding Document and Activites
             */
            case '30':
                $rules['options.posting_to_philgeps'] = ['required', 'boolean'];
                break;
            /**
             * Pre-Bid Conference
             */
            case '40':
                $rules['options.attachment'] = ['nullable', 'file'];
                break;
            /**
             * Submission and Openning of Bid/s
             */
            case '50':
                $rules['options.attachment'] = ['nullable', 'file'];
                break;
            /**
             * Bid Evaluation
             */
            case '60':
                unset($rules['options']); //Remove if no additional options
                break;
            /**
             * Post Qualification Requirements
             */
            case '70':
                $rules['options.email_sent'] = ['required', 'boolean'];
                break;
            /**
             * Post Qualification
             */
            case '80':
                $rules['options.pass_evaluation'] = ['required', 'boolean'];
                break;
            /**
             * Technical Working Group Summary Report
             */
            case '90':
                $rules['options.twg_evaluation'] = ['required', 'file'];
                $rules['options.recommended_for_award'] = ['required', 'boolean'];
                break;
            /**
             * Bid and Award Committee (BAC) Resolution
             */
            case '100':
                $rules['options.bac_resolution'] = ['required', 'file'];
                $rules['options.posted_to_philgeps'] = ['required', 'boolean'];
                break;
            /**
             * Notice of Award (NOA)
             */
            case '110':
                $rules['options.posted_to_philgeps_nkti_website'] = ['required', 'boolean'];
                break;
            /**
             * NOA Issuance & Post Performance Bond
             */
            case '120':
                $rules['options.performance_bond'] = ['required', 'file'];
                $rules['options.send_noa_to_bidders'] = ['required', 'boolean'];
                $rules['options.post_performance_bond'] = ['required', 'boolean'];
                break;
            /**
             * Purchasing Division - For Goods
             */
            case '130':
                unset($rules['options']); //Remove if no additional options
                break;
            /**
             * Business Records Management Unit - For Infra/Services
             */
            case '140':
                $rules['options.contract'] = ['required', 'file'];
                break;
            /**
             * Issuance of Notice to Proceed
             */
            case '150':
                $rules['options.notice_to_proceed'] = ['required', 'file'];
                break;
            default:
                # code...
                break;
        }

        return $rules;
    }
}
