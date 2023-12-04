<?php

namespace App\Http\Requests\V1;

use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestBac1ValidationRequest extends FormRequest
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
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'validation_status' => ['required', 'in:APPROVED,REJECTED'],
            'remarks' => ['required_if:validation_status,REJECTED', 'nullable', 'string', 'max:200'],
            'is_allowed_to_update' => ['sometimes', 'boolean'],
            'item_requests' => ['required', 'array'],
            'item_requests.*' => ['required',
                    Rule::exists('item_requests', 'id'),
                    function ($attribute, $value, $fail) {
                        $itemRequest = ItemRequest::findOrFail($value);

                        if ($itemRequest->status != ItemRequestStatus::FOR_BAC_1_APPROVAL) {
                            return $fail("The selected :attribute ({$value}) is invalid. Status must be " . ItemRequestStatus::getDescription(ItemRequestStatus::FOR_BAC_1_APPROVAL));
                        }
                    }
                ],
        ];
    }
}
