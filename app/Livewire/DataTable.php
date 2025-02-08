<?php

namespace App\Livewire;

use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public $orders;

    public $defaultInitiateDate;
    public $defaultFinalizeDate;
    public $filterData = [
        'InitiateDate' => null,
        'FinalizeDate' => null
    ];
    protected $listeners = ['updateDateRange', 'getPositionDate'];
    public function mount()
    {
        $this->defaultInitiateDate = now()->subDays(8)->startOfDay()->toDateTimeString();
        $this->defaultFinalizeDate = now()->endOfDay()->toDateTimeString();

        if (is_null($this->filterData['InitiateDate']) || is_null($this->filterData['FinalizeDate'])) {
            $this->filterData['InitiateDate'] = $this->defaultInitiateDate;
            $this->filterData['FinalizeDate'] = $this->defaultFinalizeDate;
        }
    }
    public function updateDateRange($startDate, $endDate)
    {
        $this->filterData['InitiateDate'] = $startDate;
        $this->filterData['FinalizeDate'] = $endDate;
    }

    public function updatePerPage()
    {
        $this->resetPage();
        $this->emit('refreshDataTable');
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }

    public function exportCsv()
    {
        $allData = $this->getPositionDateQuery()->get();
        return Excel::download(new OrdersExport($allData), 'position_history.csv');
    }

    public function exportXlsx()
    {
        $allData = $this->getPositionDateQuery()->get();
        return Excel::download(new OrdersExport($allData), 'position_history.xlsx');
    }
    public function exportPdf()
    {
        $allData = $this->getPositionDateQuery()->get();

        $html = View::make('exports.orders', compact('allData'))->render();

        return response()->streamDownload(
            fn() => print(PDF::loadHTML($html)->setPaper('a4')->setOption('lowquality', true)->output()),
            'position_history.pdf'
        );
    }
    public function getPositionDateQuery()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');

        $query = Position::whereIn('user_exchange_uuid', $userExchangeUuids)
            ->whereIn('position_status', ['closed'])
            ->whereNotNull('profit_loss');

        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        }

        return $query;
    }




    public function getPositionDate()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');

        $query = Position::whereIn('user_exchange_uuid', $userExchangeUuids)
            ->whereIn('position_status', ['closed'])
            ->whereNotNull('profit_loss');

        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        }
        return $query->simplePaginate($this->perPage);
    }

    public function render()
    {
        $positions = $this->getPositionDate();
        return view('livewire.data-table', [
            'positions' => $positions,
        ]);
    }

}
