<?php

use App\Livewire\PositionHistory;
use Illuminate\Support\Facades\Route;
use App\Livewire\Login;
use App\Livewire\Dashboard;
use App\Livewire\Logout;


Route::get('/', function () {
    return redirect()->to('/login');
});

Route::group(['middleware'=>'guest'], function(){
    Route::get('/login', Login::class)->name('login');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/history', PositionHistory::class)->name('positionHistory');
    Route::get('/logout', Logout::class)->name('logout');
});
