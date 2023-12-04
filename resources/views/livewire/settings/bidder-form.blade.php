<form wire:submit="save" class="row g-3">
    @include('includes.session-messages.success')
    @include('includes.session-messages.error')

    <div class="col-12">
        <label for="company_name" class="form-label">Company Name</label>
        <input type="text" wire:model="company_name" class="form-control @error('company_name') is-invalid @enderror" id="company_name">
        @error('company_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="contact_person_name" class="form-label">Contact Person - Name</label>
        <input type="text" wire:model="contact_person_name" class="form-control @error('contact_person_name') is-invalid @enderror" id="contact_person_name">
        @error('contact_person_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="contact_person_position" class="form-label">Contact Person - Position</label>
        <input type="text" wire:model="contact_person_position" class="form-control @error('contact_person_position') is-invalid @enderror" id="contact_person_position">
        @error('contact_person_position')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="contact_person_mobile" class="form-label">Contact Person - Mobile Number</label>
        <input type="text" wire:model="contact_person_mobile" class="form-control @error('contact_person_mobile') is-invalid @enderror" id="contact_person_mobile">
        @error('contact_person_mobile')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="contact_person_telephone" class="form-label">Contact Person - Telephone</label>
        <input type="text" wire:model="contact_person_telephone" class="form-control @error('contact_person_telephone') is-invalid @enderror" id="contact_person_telephone">
        @error('contact_person_telephone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="contact_person_email" class="form-label">Contact Person - Email</label>
        <input type="text" wire:model="contact_person_email" class="form-control @error('contact_person_email') is-invalid @enderror" id="contact_person_email">
        @error('contact_person_email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-12">
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
