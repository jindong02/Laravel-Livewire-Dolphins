<?php

namespace App\Http\Requests\V1;

use App\Enums\BidType;
use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestBudgetValidationPerDeptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'validation_status' => 'Status',
            'remarks' => 'Remarks',
            'is_allowed_to_update' => 'Require for update',
            'item_requests' => 'Item Requests',
            'item_requests.*' => 'Item Request',
            'item_requests.*.bid_type' => 'Bid Type',
        ];
    }

    /**
     * Get custom mesage
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'item_requests.required' => 'Please select at least one :attribute.',
            'item_requests.*.bid_type.required' => 'Please select at least one :attribute.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        /**
         * Sample Data Structure
         */
        $data = [
            "validation_status" => "APPROVED",
            "item_requests" =>
                [
                    1 => [ //Ket 1 = Department ID
                        "bid_type" => [
                            0 => "LINE",
                        ],
                    ],
                    2 => [  //Key 2 = Department ID
                        "bid_type" => [
                            0 => "LINE",
                            1 => "LOT",
                        ],
                    ],
                ],
            "remarks" => null,
            "is_allowed_to_update" => "1",
        ];

        return [
            'validation_status' => ['required', 'in:APPROVED,REJECTED'],
            'remarks' => ['required_if:validation_status,REJECTED', 'nullable', 'string', 'max:200'],
            'is_allowed_to_update' => ['sometimes', 'boolean'],
            'item_requests' => ['required', 'array',
                    function ($attribute, $value, $fail) {
                        foreach ($value as $k => $v) {
                            $dept = Department::where('id', $k)->active()->first();
                            if (!$dept) {
                                return $fail("The selected department ({$k}) is invalid.");
                            }
                        }
                    },
                ],
            // 'item_requests.*.department_id' => ['required',
            //         Rule::exists('department_id', 'id')->where('is_active', true),
            //     ],
            'item_requests.*.bid_type' => ['required', 'array'],
            'item_requests.*.bid_type.*' => ['required', 'in:' . implode(',', BidType::getValues())],
        ];
    }
}
