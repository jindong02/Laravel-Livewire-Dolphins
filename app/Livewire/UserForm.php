<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UserForm extends Component
{
    public $id;
    public $last_name = '';
    public $first_name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $department_id = '';
    public $role = '';
    public $current_role = '';

    public function saveUser()
    {
        $this->validate();
        $data = $this->all();
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }
        else {
            unset($data['password'], $data['password_confirmation']);
        }


        if (!filled($this->id)) {
            unset($data['id']);
            $this->createUser($data);
        }
        else  $this->updateUser($data);
    }

    public function createUser($data)
    {
        $user = User::create($data);

        $role = Role::where('name', $data['role'])->first();
        $user->assignRole($role->name);

        session()->flash('success', 'User successfully created');

        $this->redirect(route('users.show', $user->id), navigate: false);
    }

    public function updateUser($data)
    {
        $user = User::find($this->id);
        $user->update($data);

        if ($data['role'] !== $data['current_role']) {
            $user->syncRoles([]);

            $role = Role::where('name', $data['role'])->first();
            $user->assignRole($role->name);
        }

        session()->flash('success', 'User successfully updated');

        $this->redirect(route('users.show', $user->id), navigate: false);
    }

    public function render()
    {
        $departments = Department::active()->orderBy('name', 'ASC')->get();

        $roles = Role::pluck('name')->toArray();
        return view('livewire.user-form', compact('departments', 'roles'));
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
            'last_name' => ['required', 'max:60'],
            'first_name' => ['required', 'max:60'],
            'email' => ['required', 'unique:users,email' . $id],
            'password' => ['required', 'confirmed', Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'role' => ['required', 'exists:roles,name'],
        ];

        if (filled($this->id)) {
            $rules['password'] = ['sometimes', 'nullable', 'confirmed', Password::defaults()];
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
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'email' => 'Email',
            'password' => 'Password',
            'department_id' => 'Department',
            'role' => 'Role',
        ];

        return $attributes;
    }
}
