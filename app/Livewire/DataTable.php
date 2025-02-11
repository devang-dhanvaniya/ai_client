<?php

namespace App\Livewire;

use App\Models\Position;
use App\Models\UserExchangeDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
    public $selectedAccount = '';
    protected $listeners = ['updateDateRange', 'getPositionDate'];
    public $search = '';
    public $sortByColumn = 'close_time';
    public $sortDirection = 'DESC';
    public $allAccounts = [];
    public function setSortFunctionality($columnName){
        if ($this->sortByColumn == $columnName) {
            $this->sortDirection = ($this->sortDirection == 'ASC') ? 'DESC' : 'ASC';
            return;
        }
        $this->sortByColumn = $columnName;
        $this->sortDirection = 'DESC';
    }
    public function mount()
    {
        $client = Auth::user();
        $userExchangeIds = $client->exchangeDetails()->pluck('tbl_user_exchange_details.id');
        $this->allAccounts = UserExchangeDetail::distinct()
            ->whereIn('id', $userExchangeIds)
            ->get(['account_nickname', 'user_exchange_uuid','account_login'])->toArray();

        $this->defaultInitiateDate = now()->startOfDay()->toDateTimeString();
        $this->defaultFinalizeDate = now()->endOfDay()->toDateTimeString();

        $startDate = Session::get('historyStartDate');
        $endDate = Session::get('historyEndDate');
        if($startDate && $endDate){
            $this->filterData['InitiateDate'] = $startDate;
            $this->filterData['FinalizeDate'] = $endDate;
        }else {
            if (is_null($this->filterData['InitiateDate']) || is_null($this->filterData['FinalizeDate'])) {
                $this->filterData['InitiateDate'] = $this->defaultInitiateDate;
                $this->filterData['FinalizeDate'] = $this->defaultFinalizeDate;
            }
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
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }
    public function resetHistoryFilters()
    {
        Session::forget('historyStartDate');
        Session::forget('historyEndDate');
        $this->selectedAccount = '';
        $this->render();
    }
    public function storeHistoryDates($startDate, $endDate)
    {
        Session::put('historyStartDate', $startDate);
        Session::put('historyEndDate', $endDate);
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
        $pdf = PDF::loadHTML($html)
            ->setPaper('A4')
            ->setOption('lowquality', true);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'position_history.pdf'
        );
    }
    public function getPositionDateQuery()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');

        $query = Position::with('walletConfig')->whereIn('position_status', ['closed'])->whereNotNull('profit_loss');

        if (!empty($this->selectedAccount)) {
            $query->where('user_exchange_uuid', $this->selectedAccount);
        }else{
            $query->whereIn('user_exchange_uuid', $userExchangeUuids);
        }
        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        }
        $query->orderBy($this->sortByColumn,$this->sortDirection);
        return $query;
    }
    public function getPositionDate()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');

        $query = Position::with('walletConfig')->whereIn('position_status', ['closed'])->whereNotNull('profit_loss');

        if (!empty($this->selectedAccount)) {
            $query->where('user_exchange_uuid', $this->selectedAccount);
        }else{
            $query->whereIn('user_exchange_uuid', $userExchangeUuids);
        }
        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        }
        $query->orderBy($this->sortByColumn,$this->sortDirection);
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
