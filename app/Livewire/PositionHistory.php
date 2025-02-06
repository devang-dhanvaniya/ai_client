<?php

namespace App\Livewire;

use App\Exports\OrdersExport;
use App\Models\Position;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PositionHistory extends Component
{

    use WithPagination;

    public $perPage = 10;

    public function updatedPerPage()
    {
        $this->resetPage(); // Reset pagination when changing perPage
    }

    public function exportCsv()
    {
        return Excel::download(new OrdersExport(Position::all()), 'orders.csv');
    }

    public function exportXlsx()
    {
        return Excel::download(new OrdersExport(Position::all()), 'orders.xlsx');
    }

    public function exportPdf()
    {
        $orders = Position::select('symbol', 'side', 'profit_loss', 'volume', 'open_price', 'close_price', 'order_uuid', 'open_time', 'close_time')
            ->get();

        $html = View::make('exports.orders', compact('orders'))->render();

        return response()->streamDownload(
            fn() => print(PDF::loadHTML($html)->setPaper('a4')->setOption('lowquality', true)->output()),
            'orders.pdf'
        );
    }

    public function render()
    {
        return view('livewire.position-history', [
            'orders' => Position::orderBy('created_at', 'desc')
            ->paginate($this->perPage)
        ]);
    }

}
