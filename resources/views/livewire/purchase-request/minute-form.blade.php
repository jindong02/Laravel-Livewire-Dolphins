<form wire:submit="saveMinute" class="row g-3">

    @include('includes.session-messages.success')
    @include('includes.session-messages.error')
    @include('includes.session-messages.form-errors')

    @if ($is_allowed_to_update)
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-outline-primary show-loading-on-click" style="width: 200px">
                SAVE
            </button>
        </div>
    @endif
    <div class="col-12">
        <label for="notes" class="form-label">Meeting Minutes</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" wire:model="notes" id="notes" ></textarea>
        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="memo_attachment" class="form-label">Attachment for Minutes</label>
        <input type="file" class="form-control @error('memo_attachment') is-invalid @enderror" wire:model="memo_attachment" id="memo_attachment">
        @error('memo_attachment')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-6">
        <label for="memo_date" class="form-label">MOM Date</label>
        <input type="date" class="form-control @error('memo_date') is-invalid @enderror" wire:model="memo_date" id="memo_date">
        @error('memo_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    @foreach ($templates as $template)
        <div class="col-6">
            @if ($template->data_type == 'boolean')
                <div class="form-check form-switch">
                    <input class="form-check-input @error("options." . $template->key) is-invalid @enderror" type="checkbox" role="switch" wire:model="options.{{$template->key}}" name="options.{{$template->key}}" value="1">
                    <label class="form-check-label" for="options.{{$template->key}}">{{ $template->label }}</label>
                </div>
                @error("options." . $template->key)
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            @endif
        </div>
    @endforeach

</form>
