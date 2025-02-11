<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class Logout extends Component
{
    public function logout()
    {
        Session::forget('historyStartDate');
        Session::forget('historyEndDate');
        Session::forget('dashboardStartDate');
        Session::forget('dashboardEndDate');
        Auth::logout();

        return $this->redirectRoute('login', navigate: true);
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
