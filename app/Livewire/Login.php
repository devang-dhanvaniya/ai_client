<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
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
        $client = User::where('client_email', $this->client_email)->first();
        if ($client && Hash::check($this->client_password, $client->client_password)) {
            Auth::login($client);
            session()->flash('message', 'You have successfully logged in!');
            return $this->redirectRoute('dashboard', navigate: true);
        }
        session()->flash('error', 'Invalid credentials!');
    }
    public function render()
    {
        return view('livewire.login');
    }
}
