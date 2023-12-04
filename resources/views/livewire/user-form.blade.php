<form wire:submit="saveUser" class="row g-3">
    @include('includes.session-messages.success')
    @include('includes.session-messages.error')
    <div class="col-6">
        <label for="department_id" class="form-label">Department</label>
        <select id="department_id" wire:model="department_id" class="form-select @error('department_id') is-invalid @enderror">
            <option value="" hidden> Please select department</option>
            @foreach ($departments as $department )
                <option value="{{$department->id}}">
                    {{$department->name}}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-6">
        <label for="role" class="form-label">Role
            @if(filled($current_role))
                <small>(Current: {{ $current_role }})</small>
            @endif
        </label>
        <select id="role" wire:model="role" class="form-select @error('role') is-invalid @enderror">
            <option value="" hidden> Please select role</option>
            @foreach ($roles as $role )
                <option>{{$role}}</option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-4">
        <label for="first_name" class="form-label @error('first_name') is-invalid @enderror">First Name</label>
        <input type="text" wire:model="first_name" class="form-control" id="first_name">
        @error('first_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-4">
        <label for="last_name" class="form-label @error('last_name') is-invalid @enderror">Last Name</label>
        <input type="text" wire:model="last_name" class="form-control" id="last_name">
        @error('last_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-4">
        <label for="email" class="form-label @error('email') is-invalid @enderror">Email Address</label>
        <input type="email" wire:model="email" class="form-control" id="email">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-4">
        <label for="password" class="form-label @error('password') is-invalid @enderror">{{ filled($id) ? 'New ' : '' }}Password</label>
        <input type="text" wire:model="password" class="form-control" id="password">
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-4">
        <label for="password_confirmation" class="form-label @error('password_confirmation') is-invalid @enderror">{{ filled($id) ? 'New ' : '' }}Password Confirmation</label>
        <input type="text" wire:model="password_confirmation" class="form-control" id="password_confirmation">
        @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:key="saveUser" style="width: 200px" wire:loading.attr="disabled">
            {{ filled($id) ? 'Save' : 'Create'}}
        </button>
    </div>
</form>
