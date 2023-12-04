<?php

namespace App\Livewire\PurchaseRequest;

use App\Models\MinuteTemplate;
use App\Models\PurchaseRequest;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MinuteForm extends Component
{
    public $id;
    public $status_code;
    public $status;
    public $memo_date = '';
    public $notes = '';
    public $memo_attachment;
    public $options = [];
    public $templates = [];
    public $is_allowed_to_update = false;

    public function saveMinute()
    {
        $this->validate();

        $data = [
            'notes' => $this->notes,
            'memo_date' => $this->memo_date,
            'options' => $this->options,
        ];

        $purchaseRequest = PurchaseRequest::where('id', $this->id)->with(['minutes'])->firstOrFail();
        $minute = $purchaseRequest->currentMinute;
        if ($minute) {
            $minute->update($data);

            session()->flash('success', 'Minute successfully updated');
        }
        else {
            $data['status'] = $purchaseRequest->status;
            $purchaseRequest->minutes()->create($data);
            session()->flash('success', 'Minute successfully created');
        }
    }

    public function render()
    {
        if ($this->memo_date == '') {
            $this->memo_date = now()->toDateString();
        }

        foreach ($this->templates as $template) {
            if (!array_key_exists($template->key, $this->options)) {
                $this->options[$template->key] = null;
            }
        }

        return view('livewire.purchase-request.minute-form');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'id' => ['required', Rule::exists('purchase_requests', 'id')],
            'notes' => ['nullable', 'string', 'max:400'],
            'memo_date' => ['required', 'date'],
            // 'memo_attachment' => ['sometimes', 'nullable', 'file', 'max:2000'],
            'options' => ['nullable', 'array'],
        ];

        foreach ($this->templates as $template) {
            $rule["options.{$template->key}"] = ['required', $template->data_type];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function validationAttributes()
    {
        $attributes = [
            'id' => 'Purchase Request',
            'notes' => 'Meeting Minutes',
            'memo_date' => 'MOM Date',
            'memo_attachment' => 'Attachment for Minutes',
            'options' => 'Options',
        ];

        foreach ($this->templates as $template) {
            $attributes["options.{$template->key}"] = $template->label;
        }

        return $attributes;
    }
}
