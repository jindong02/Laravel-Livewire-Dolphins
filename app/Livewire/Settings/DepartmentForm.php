<?php

namespace App\Livewire\Settings;

use App\Models\Department;
use Livewire\Component;

class DepartmentForm extends Component
{

    public $id = '';
    public $name = '';
    public $is_active = true;

    public function saveDepartment()
    {
        $this->validate();
        $data = $this->all();

        if (!filled($this->id)) {
            unset($data['id']);
            $this->createDepartment($data);
        }
        else  $this->updateDepartment($data);
    }

    public function createDepartment($data)
    {
        $department = Department::create($data);

        session()->flash('success', 'Department successfully created');

        $this->redirect(route('settings.departments.show', $department->id), navigate: false);
    }

    public function updateDepartment($data)
    {
        $department = Department::find($this->id);
        $department->update($data);

        session()->flash('success', 'Department successfully updated');

        $this->redirect(route('settings.departments.show', $department->id), navigate: false);
    }

    public function render()
    {
        return view('livewire.settings.department-form');
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
            'name' => ['required', 'unique:departments,name' . $id],
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
            'name' => 'Name',
            'is_active' => 'Active',
        ];

        return $attributes;
    }
}
