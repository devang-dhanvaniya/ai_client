<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
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
            \Log::info('Password check passed for client:', ['client_id' => $client->client_id]);

            //dd($this->client_password, $client->client_password);
            if(Auth::login($client)){
                //dd("login");
                \Log::info('Login successfully');
            }else{
                \Log::error('Auth::login failed for client:', ['client_id' => $client->client_id]);
                session()->flash('error', 'Login failed!');
                dd("no login");
            }

            session()->flash('message', 'You have successfully logged in!');
            return $this->redirectRoute('dashboard', navigate: true);
        } else {
            session()->flash('error', 'Invalid credentials!');
        }
//        if(Auth::attempt($credentials))
//        {
//            session()->flash('message', 'You have successfully logged in!');
//
//            return $this->redirectRoute('dashboard', navigate: true);
//        }else{
//            dd("dffdfdf");
//        }

        session()->flash('error', 'Invalid credentials!');
    }
    public function render()
    {
        return view('livewire.login');
    }
}
