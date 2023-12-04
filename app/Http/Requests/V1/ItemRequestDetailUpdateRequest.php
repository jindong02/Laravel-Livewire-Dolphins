<?php

namespace App\Http\Requests\V1;

use App\Models\ItemRequest;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequestDetailUpdateRequest extends FormRequest
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
        return [
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:250'],
            'attachment' => ['sometimes', 'file', 'max:2000'],
        ];
    }
}
