<?php

namespace App\Http\Requests\V1;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequestDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestDetailDepartmentValidationRequest extends FormRequest
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
            'validation_status' => 'Status',
            'remarks' => 'Remarks',
            'is_allowed_to_update' => 'Require for update',
            'item_request_details' => 'Items',
            'item_request_details.*' => 'Item',
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
            'validation_status' => ['required', 'in:' . implode(',', [ItemRequestDetailStatus::APPROVED, ItemRequestDetailStatus::REJECTED])],
            'remarks' => ['required_if:validation_status,REJECTED', 'nullable', 'string', 'max:200'],
            'is_allowed_to_update' => ['required_if:validation_status,REJECTED', 'nullable', 'boolean'],
            'item_request_details' => ['required', 'array'],
            'item_request_details.*' => ['sometimes',
                    Rule::exists('item_request_details', 'id'),
                    function ($attribute, $value, $fail) {
                        $itemRequestDetail = ItemRequestDetail::findOrFail($value);
                        $itemRequest = $itemRequestDetail->itemRequest;
                        $user = request()->user;
                        if ($itemRequest->department_id != $user->department_id) {
                            return $fail("The selected :attribute ({$value}) is invalid. :attribute comes from a different Department");
                        }

                        if ($itemRequest->validation_status != ItemRequestStatus::FOR_DEPARTMENT_APPROVAL) {
                            return $fail("The selected :attribute ({$value}) is invalid. Status must be " . ItemRequestStatus::getDescription(ItemRequestStatus::FOR_DEPARTMENT_APPROVAL));
                        }

                        if ($itemRequestDetail->status != ItemRequestDetailStatus::FOR_APPROVAL) {
                            return $fail("The selected :attribute ({$value}) is invalid. Status must be " . ItemRequestDetailStatus::getDescription(ItemRequestDetailStatus::FOR_APPROVAL));
                        }
                    }
                ],
        ];
    }
}
