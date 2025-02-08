<div class="loginmain">

    <div class="logincard">
        <div class="login-header">Please Login to your account</div>
        <div class="card-body">
            <form wire:submit="login">
                <div class="py-4">
                    <label for="email" class="text-start">Email Address</label>
                    <div>
                        <input type="email" class="form-control @error('client_email') is-invalid @enderror"
                            id="email" wire:model="client_email">
                        @if ($errors->has('client_email'))
                            <span class="text-danger">{{ $errors->first('client_email') }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <label for="password" class="text-start">Password</label>
                    <div class="eye_posiotin">
                        <input type="password" class="form-control @error('client_password') is-invalid @enderror"
                            id="password" wire:model="client_password">
                        <span id="togglePassword" style="cursor: pointer;">
                            <i class="fa-regular fa-eye-slash" id="toggleIcon"></i>
                        </span>
                        {{-- <i class="fa-regular fa-eye-slash"></i> --}}
                        {{-- <i class="fa-regular fa-eye-slash"></i> --}}
                        @if ($errors->has('client_password'))
                            <span class="text-danger">{{ $errors->first('client_password') }}</span>
                        @endif
                        @if (session()->has('error'))
                            <span class="text-danger">{{ session('error') }}</span>
                        @endif
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-100 btn btn-primary">
                        Login
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@livewireScripts
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        // Toggle password visibility
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    });
</script>
