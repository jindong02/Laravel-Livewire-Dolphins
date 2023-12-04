<?php

namespace App\Http\Requests\V1;

use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequestDetailCreateRequest extends FormRequest
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
            'sku' => 'Item',
            'quantity' => 'Quantity',
            'unit_cost' => 'Unit Cost',
            'description' => 'Description',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = request()->route()->parameter('item_request');
        return [
            'sku' => ['required',
                    Rule::exists('items', 'sku')->where('is_active', true),
                    function ($attribute, $value, $fail) use ($id) {
                        $item = ItemRequestDetail::where('item_request_id', $id)->where('sku', $value)->first();
                        if ($item) {
                            $fail("The selected :attribute is already exist in the Item Request.");
                        }
                    },
                ],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:250'],
            'attachment' => ['sometimes', 'file', 'max:2000'],
        ];
    }
}
