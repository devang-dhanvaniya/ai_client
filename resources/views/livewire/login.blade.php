<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Please Login to your account</div>
            <div class="card-body">
                <form wire:submit="login">
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('client_email') is-invalid @enderror" id="email" wire:model="client_email">
                            @if ($errors->has('client_email'))
                                <span class="text-danger">{{ $errors->first('client_email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('client_password') is-invalid @enderror" id="password" wire:model="client_password">
                            @if ($errors->has('client_password'))
                                <span class="text-danger">{{ $errors->first('client_password') }}</span>
                            @endif
                            @if (session()->has('error'))
                                <span class="text-danger">{{ session('error') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <button type="submit" class="col-md-3 offset-md-5 btn btn-primary">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
