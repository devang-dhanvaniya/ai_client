<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionHistory extends Component
{

    use WithPagination;

    public $perPage = 10;

    public function updatedPerPage()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.position-history', [
            'orders' => Position::orderBy('created_at', 'desc')
            ->paginate($this->perPage)
        ]);
    }

}
