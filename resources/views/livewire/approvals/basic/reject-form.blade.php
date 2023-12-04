<form wire:submit="rejectItems" class="row g-3">
    <div class="col-12">
        <label for="remarks" class="form-label">Reason</label>
        <textarea class="form-control @error('remarks') is-invalid @enderror"" wire:model="remarks" id="remarks" name="remarks"></textarea>
        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input @error('is_allowed_to_update') is-invalid @enderror" type="checkbox" role="switch" wire:model="is_allowed_to_update" name="is_allowed_to_update" value="1">
            <label class="form-check-label" for="is_allowed_to_update">Allow User to update this request</label>
        </div>
    </div>


    <div class="d-flex justify-content-end border-top pt-3">
        <button type="submit" class="btn btn-danger" style="width: 200px">Reject</button>
    </div>
</form>
