<form wire:submit="saveRole" class="row g-3">
    @include('includes.session-messages.success')
    @include('includes.session-messages.error')
    @include('includes.session-messages.form-errors')
    <div class="col-12">
        <label for="name" class="form-label">Name</label>
        <input type="text" wire:model="name" wire:change="formatName" class="form-control" id="name" @disabled($system_permission)>
    </div>
    <div class="col-12">
        <label for="name" class="form-label">Permissions</label>
    </div>
    @foreach ($availablePermissions as $permission)
        <div class="col-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" wire:model="permissions" id="role_{{ strtolower($permission) }}" value="{{ $permission }}">
                <label class="form-check-label" for="role_{{ strtolower($permission) }}">{{ \App\Enums\Permission::getDescription($permission) }}</label>
            </div>
        </div>
    @endforeach
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:key="saveUser" style="width: 200px" wire:loading.attr="disabled">
            {{ filled($id) ? 'Save' : 'Create'}}
        </button>
    </div>
</form>
