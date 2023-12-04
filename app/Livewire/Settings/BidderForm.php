<?php

namespace App\Livewire\Settings;

use App\Models\Bidder;
use Livewire\Component;

class BidderForm extends Component
{
    public $id = '';
    public $company_name = '';
    public $contact_person_name = '';
    public $contact_person_position = '';
    public $contact_person_mobile = '';
    public $contact_person_telephone = '';
    public $contact_person_email = '';
    public $is_active = true;

    public function save()
    {
        $this->validate();
        $data = $this->all();

        if (!filled($this->id)) {
            unset($data['id']);
            $this->create($data);
        }
        else  $this->update($data);
    }

    public function create($data)
    {
        $bidder = Bidder::create($data);

        session()->flash('success', 'Bidder successfully created');

        $this->redirect(route('settings.bidders.show', $bidder->id), navigate: false);
    }

    public function update($data)
    {
        $bidder = Bidder::find($this->id);
        $bidder->update($data);

        session()->flash('success', 'Bidder successfully updated');

        $this->redirect(route('settings.bidders.show', $bidder->id), navigate: false);
    }

    public function render()
    {
        return view('livewire.settings.bidder-form');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = '';
        if (filled($this->id)) {
            $id = ',' . $this->id;
        }

        $rules = [
            'company_name' => ['required', 'unique:bidders,company_name' . $id],
            'contact_person_name' => ['nullable', 'max:120'],
            'contact_person_position' => ['nullable', 'max:60'],
            'contact_person_mobile' => ['nullable', 'max:60'],
            'contact_person_telephone' => ['nullable', 'max:60'],
            'contact_person_email' => ['nullable', 'email'],
            'is_active' => ['sometimes', 'boolean'],
        ];

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
            'company_name' => 'Company Name',
            'contact_person_name' => 'Contact Person - Name',
            'contact_person_position' => 'Contact Person - Position',
            'contact_person_mobile' => 'Contact Person - Mobile Number',
            'contact_person_telephone' => 'Contact Person - Telephone',
            'contact_person_email' => 'Contact Person - Email',
            'is_active' => 'Active',
        ];

        return $attributes;
    }
}
