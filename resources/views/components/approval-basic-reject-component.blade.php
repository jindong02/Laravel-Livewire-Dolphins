<!-- Modal -->
<div class="modal fade" id="rejectItemRequestForm" tabindex="-1" aria-labelledby="rejectItemRequestFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="rejectItemRequestFormLabel">Reject Item Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label for="remarks" class="form-label">Reason</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" required></textarea>
                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_allowed_to_update" value="1" checked>
                        <label class="form-check-label" for="is_allowed_to_update">Allow User to update this request</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="reject">Reject</button>
            </div>
        </div>
    </div>
</div>
