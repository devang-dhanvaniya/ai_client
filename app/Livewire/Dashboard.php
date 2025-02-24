<?php

namespace App\Livewire;

use App\Models\Position;
use App\Models\UserExchangeDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedFilter = '';
    public $filterData = [
        'InitiateDate' => null,
        'FinalizeDate' => null
    ];
    public $aggregates;
    public $allAccounts = [];
    public $chartDataFormatted = [];
    public $defaultInitiateDate;
    public $defaultFinalizeDate;
    public function mount()
    {
        $client = Auth::user();
        $userExchangeIds = $client->exchangeDetails()->pluck('tbl_user_exchange_details.id');
        $this->allAccounts = UserExchangeDetail::distinct()
            ->whereIn('id', $userExchangeIds)
            ->get(['account_nickname', 'user_exchange_uuid','account_login'])->toArray();

        $this->defaultInitiateDate = now()->startOfDay()->toDateTimeString();
        $this->defaultFinalizeDate = now()->endOfDay()->toDateTimeString();

        $startDate = Session::get('dashboardStartDate');
        $endDate = Session::get('dashboardEndDate');

        if($startDate && $endDate){
            $this->filterData['InitiateDate'] = $startDate;
            $this->filterData['FinalizeDate'] = $endDate;
        }else{
            if (is_null($this->filterData['InitiateDate']) || is_null($this->filterData['FinalizeDate'])) {
                $this->filterData['InitiateDate'] = $this->defaultInitiateDate;
                $this->filterData['FinalizeDate'] = $this->defaultFinalizeDate;
            }
        }
    }
    public function applyFilters()
    {
        $this->getDashboardData();
    }
    public function resetFilters()
    {
        Session::forget('dashboardStartDate');
        Session::forget('dashboardEndDate');
        $this->selectedFilter = '';
        $this->getDashboardData();
    }

    public function storeDashboardDates($startDate, $endDate)
    {
        Session::put('dashboardStartDate', $startDate);
        Session::put('dashboardEndDate', $endDate);
    }
    public function getDashboardData()
    {
        $client = Auth::user();
        $userExchangeUuids = $client->exchangeDetails()->pluck('tbl_user_exchange_details.user_exchange_uuid');
        $query = Position::whereIn('position_status', ['closed'])->whereNotNull('profit_loss');

        if (!empty($this->selectedFilter)) {
            $query->where('user_exchange_uuid', $this->selectedFilter);
        }else{
            $query->whereIn('user_exchange_uuid', $userExchangeUuids);
        }
        if ($this->filterData['InitiateDate'] != null && $this->filterData['FinalizeDate'] != null) {
            $query->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        } else {
            $query->where('close_time', '>=', now()->subDays(6)->startOfDay()->toDateTimeString());
        }

        $baseQuery = clone $query;

        $aggregates = $baseQuery->select(
            DB::raw('SUM(profit_loss) as total_pnl'),
            DB::raw('COUNT(CASE WHEN profit_loss >= 0 THEN 1 END) as total_win_trades'),
            DB::raw('COUNT(CASE WHEN profit_loss < 0 THEN 1 END) as total_loss_trades'),
            DB::raw('SUM(CASE WHEN profit_loss >= 0 THEN profit_loss ELSE 0 END) as total_profit'),
            DB::raw('SUM(CASE WHEN profit_loss < 0 THEN profit_loss ELSE 0 END) as total_loss'),
            DB::raw('MIN(CASE WHEN profit_loss < 0 THEN CAST(profit_loss AS DECIMAL(25,8)) END) AS max_loss'),
            DB::raw('MAX(CASE WHEN profit_loss > 0 THEN CAST(profit_loss AS DECIMAL(25,8)) END) AS max_win'),
            DB::raw('AVG(profit_loss) AS avg_profit_loss'),
            DB::raw('SUM(usd_amount) AS total_usd_amount'),
            DB::raw('COUNT(*) as total_trades'),
        )->first();

        $totalTrades = $aggregates->total_trades ?? 0;
        $totalWinTrades = $aggregates->total_win_trades ?? 0;

        $totalWinRatio = $totalTrades > 0 ? round(($totalWinTrades / $totalTrades) * 100, 2) : 0;

        $this->aggregates = [
            'total_pnl' => (float)$aggregates->total_pnl ?? 0,
            'total_profit' => (float)$aggregates->total_profit ?? 0,
            'total_loss' => (float)$aggregates->total_loss ?? 0,
            'total_win_trades' => $aggregates->total_win_trades ?? 0,
            'total_loss_trades' => $aggregates->total_loss_trades ?? 0,
            'max_loss' => round($aggregates->max_loss ?? 0, 8),
            'max_win' => round($aggregates->max_win ?? 0, 8),
            'total_win_ratio' => $totalWinRatio,
            'total_trades' => $aggregates->total_trades ?? 0
        ];

        $query2 = Position::selectRaw('DATE(close_time) as date, SUM(profit_loss) as daily_profit_loss')
            ->whereIn('position_status', ['closed'])
            ->whereNotNull('profit_loss')
            ->groupBy(DB::raw('DATE(close_time)'))
            ->orderBy('date', 'ASC');

        if (!empty($this->selectedFilter)) {
            $query2->where('user_exchange_uuid', $this->selectedFilter);
        }else{
            $query2->whereIn('user_exchange_uuid', $userExchangeUuids);
        }

        if (!empty($this->filterData['InitiateDate']) && !empty($this->filterData['FinalizeDate'])) {
            $query2->whereBetween('close_time', [$this->filterData['InitiateDate'], $this->filterData['FinalizeDate']]);
        } else {
            $query2->where('close_time', '>=', now()->subDays(6)->startOfDay()->toDateTimeString());
        }

        $chartData = $query2->get();
        $this->chartDataFormatted = [
            'labels' => $chartData->pluck('date')->toArray(),
            'data' => $chartData->pluck('daily_profit_loss')->toArray(),
            'colors' => $chartData->map(function ($item) {
                return $item->daily_profit_loss >= 0 ? '#82ca9d' : '#ff96a0';
            })->toArray(),
        ];
    }

    public function render()
    {
        $this->getDashboardData();
        $this->dispatch('chartDatas', chartDatas: $this->chartDataFormatted);
        return view('livewire.dashboard');
    }
}
