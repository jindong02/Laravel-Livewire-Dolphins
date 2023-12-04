<?php

namespace App\Http\Requests\V1;

use App\Enums\BidType;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $id = request()->route()->parameter('item_request');
        $itemRequest = ItemRequest::findOrFail($id);

        return $itemRequest->isAllowedToUpdate();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'mode_id' => 'Mode',
            'fund_source_id' => 'Source of Fund',
            'supply_type_id' => 'Supply Type',
            'method' => 'Method',
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
            'mode_id' => ['required', Rule::exists('modes', 'id')->where('is_active', true)],
            'fund_source_id' => ['required', Rule::exists('fund_sources', 'id')->where('is_active', true)],
            'supply_type_id' => ['required', Rule::exists('supply_types', 'id')->where('is_active', true)],
            'method' => ['nullable', 'string'],
        ];
    }
}
