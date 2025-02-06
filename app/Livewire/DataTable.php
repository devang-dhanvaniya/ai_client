<?php

namespace App\Livewire;

use App\Models\Position;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DataTable extends Component
{
    use WithPagination;
    public $perPage = 50;

    public function updatePerPage()
    {
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->page = $page;
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
        $orders = Position::simplePaginate($this->perPage);

        return view('livewire.data-table', compact('orders'));
    }

}
