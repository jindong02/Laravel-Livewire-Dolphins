<?php

namespace App\Http\Requests\V1;

use App\Enums\BidType;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestCreateRequest extends FormRequest
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
            'bid_type' => 'Bid Type',
            'mode_id' => 'Mode',
            'fund_source_id' => 'Source of Fund',
            'supply_type_id' => 'Supply Type',
            'method' => 'Method',
            'status' => 'Status',
            'items.*.sku' => 'Item',
            'items.*.quantity' => 'Quantity',
            'items.*.unit_cost' => 'Unit Cost',
            'items.*.description' => 'Description',
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
            'bid_type' => ['required', 'in:'. implode(BidType::getValues())],
            'mode_id' => ['required', Rule::exists('modes', 'id')->where('is_active', true)],
            'fund_source_id' => ['required', Rule::exists('fund_sources', 'id')->where('is_active', true)],
            'supply_type_id' => ['required', Rule::exists('supply_types', 'id')->where('is_active', true)],
            'method' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:'. implode(',', [ItemRequestStatus::DRAFT, ItemRequestStatus::nextStatus(ItemRequestStatus::DRAFT)])],
            'items' => ['required', 'array'],
            'items.*.sku' => ['required',
                    Rule::exists('items', 'sku')->where('is_active', true),
                    function ($attribute, $value, $fail) {
                        // Check if the SKU is unique within the request
                        $skus = collect($this->input('items'))->pluck('sku');
                        if ($skus->count() !== $skus->unique()->count()) {
                            $fail("The :attribute must be unique within the request.");
                        }
                    },
                ],
            'items.*.unit_cost' => ['required', 'numeric', 'gt:0'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.description' => ['nullable', 'string', 'max:250']
        ];
    }
}
