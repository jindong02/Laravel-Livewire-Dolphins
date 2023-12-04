<?php

namespace App\Livewire;

use App\Enums\Permission;
use App\Models\Role;
use Livewire\Component;

class RoleForm extends Component
{
    public $id;
    public $name = '';
    public $permissions = [];
    public $system_permission = false;

    public function saveRole()
    {
        $this->validate();
        $data = $this->all();

        if (!filled($this->id)) {
            unset($data['id']);
            $this->createRole($data);
        }
        else  $this->updateRole($data);
    }

    public function createRole($data)
    {
        $role = Role::create([
            'name' => $data['name'],
        ]);
        $role->syncPermissions($this->permissions);

        session()->flash('success', 'Role successfully created');

        $this->redirect(route('roles.show', $role->name), navigate: false);
    }

    public function updateRole($data)
    {
        $role = Role::find($this->id);
        $role->update($data);

        $role->syncPermissions($this->permissions);

        session()->flash('success', 'Role successfully updated');

        $this->redirect(route('roles.show', $role->name), navigate: false);
    }

    public function formatName()
    {
        $this->name = strtoupper($this->name);
    }

    public function render()
    {
        $availablePermissions = Permission::getValues();
        return view('livewire.role-form', compact('availablePermissions'));
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

        $availablePermissions = Permission::getValues();
        $rules = [
            'name' => ['required', 'unique:roles,name' . $id],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'in:' . implode(',', $availablePermissions)],

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
            'permissions' => 'Permission',
        ];
        $availablePermissions = Permission::getValues();
        foreach ($availablePermissions as $permission) {
            $attributes['permissions.' . $permission] = Permission::getDescription($permission);
        }

        return $attributes;
    }

    public function messages()
    {
        return [
            'permissions.required' => 'Please select at least one :attribute.',
        ];
    }
}
