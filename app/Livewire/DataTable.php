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

class DataTable extends DateFilterComponent
{
    use WithPagination;
    public $perPage = 50;
    public $orders;
    public $search = '';
    public $sortByColumn = 'close_time';
    public $sortDirection = 'DESC';
    protected $listeners = ['updateDateRange', 'getPositionData', 'dateRangeUpdated'];

    public function setSortFunctionality($columnName){
        if ($this->sortByColumn == $columnName) {
            $this->sortDirection = ($this->sortDirection == 'ASC') ? 'DESC' : 'ASC';
            return;
        }
        $this->sortByColumn = $columnName;
        $this->sortDirection = 'DESC';
    }
    public function dateRangeUpdated($startDate, $endDate)
    {
        parent::dateRangeUpdated($startDate, $endDate);
        $this->getPositionData();
    }
//    public function dateRangeUpdated($startDate, $endDate)
//    {
//        Log::info($startDate."in datatable");
//        $this->filterData['InitiateDate'] = $startDate;
//        $this->filterData['FinalizeDate'] = $endDate;
//        Log::info($this->filterData);
//        $this->resetPage();
//    }
    public function resetDateRange()
    {
        parent::resetDateRange();
        $this->resetPage();
    }
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
        $query->orderBy($this->sortByColumn,$this->sortDirection);
        return $query;
    }
    public function getPositionData()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');

        $query = Position::whereIn('user_exchange_uuid', $userExchangeUuids)
            ->whereIn('position_status', ['closed'])
            ->whereNotNull('profit_loss');

        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        }
        $query->orderBy($this->sortByColumn,$this->sortDirection);
        return $query->simplePaginate($this->perPage);
    }

    public function render()
    {
        $positions = $this->getPositionData();
        return view('livewire.data-table', [
            'positions' => $positions,
        ]);
    }

}
