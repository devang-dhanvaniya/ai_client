<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class Login extends Component
{
    #[Validate('required|email')]
    public $client_email;

    #[Validate('required')]
    public $client_password;

    public function login()
    {

        $this->validate();

        $credentials = [
            'client_email' => $this->client_email,
            'client_password' => $this->client_password,
        ];

        $client = User::where('client_email', $this->client_email)->first();

        if ($client && Hash::check($this->client_password, $client->client_password)) {
            Log::info('Password check passed for client:', ['client_id' => $client->client_id]);

            Auth::login($client);

            Log::info('Login successful for client:', ['client_id' => $client->client_id]);

            session()->flash('message', 'You have successfully logged in!');
            return $this->redirectRoute('dashboard', navigate: true);
        }

        Log::warning('Login failed due to invalid credentials:', ['client_email' => $this->client_email]);
        session()->flash('error', 'Invalid credentials!');
    }
    public function render()
    {
        return view('livewire.login');
    }
}
