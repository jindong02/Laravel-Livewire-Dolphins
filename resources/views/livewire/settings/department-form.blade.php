<form wire:submit="saveDepartment" class="row g-3">
    @include('includes.session-messages.success')
    @include('includes.session-messages.error')

    <div class="col-12">
        <label for="name" class="form-label">Name</label>
        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name">
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-4">
        <div class="form-check form-switch">
            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch" wire:model="is_active" id="is_active" value="1">
            <label class="form-check-label" for="is_active">Active</label>
            @error('is_active')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:key="saveUser" style="width: 200px" wire:loading.attr="disabled">
            {{ filled($id) ? 'Save' : 'Create'}}
        </button>
    </div>
</form>
