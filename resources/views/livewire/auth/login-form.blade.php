<form wire:submit="login" class="row g-3">
    <div class="col-12">
        <h5 class="card-title">Login</h5>
    </div>
    <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control @error('email') is-invalid @enderror" wire:model="email" name="email" id="email">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-12">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" name="password" id="password">
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </div>
</form>
