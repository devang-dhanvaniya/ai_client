<?php

namespace App\Livewire;

use App\Models\Position;
use App\Models\UserExchangeDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public $filters = [
        'filter1' => '',
        'date_range' => '',
    ];
    public $options = [];

    public function mount()
    {
        $this->options = UserExchangeDetail::distinct()->pluck('account_nickname')->toArray();
    }

    public function applyFilters()
    {
        $this->render();
    }

    public function resetFilters()
    {
        $this->filters = [
            'filter1' => '',
            'date_range' => '',
        ];
    }

    public function render()
    {
        $query = Position::whereIn('position_status', ['closed'])->whereNotNull('profit_loss');

        if (!empty($this->filters['filter1'])) {
            $query->where('user_exchange_uuid', $this->filters['filter1']);
        }

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' to ', $this->filters['date_range']);
            $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
            $endDate = Carbon::parse(trim($dates[1]))->endOfDay();
            $query->whereBetween(DB::raw("DATE(close_time)"), [$startDate->toDateString(), $endDate->toDateString()]);
        } else {
            $query->where('close_time', '>=', Carbon::now()->subDays(8));
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

        $aggregates = [
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
            ->groupBy(DB::raw('DATE(close_time)'))
            ->orderBy('date', 'ASC');

        if (!empty($this->filters['filter1'])) {
            $query2->where('user_exchange_uuid', $this->filters['filter1']);
        }

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' to ', $this->filters['date_range']);
            $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
            $endDate = Carbon::parse(trim($dates[1]))->endOfDay();

            $query->whereBetween(DB::raw("DATE(close_time)"), [$startDate->toDateString(), $endDate->toDateString()]);
        } else {
            $query2->where('close_time', '>=', Carbon::now()->subDays(8));
        }

        $chartData = $query2->get();

        $chartDataFormatted = [
            'labels' => $chartData->pluck('date')->toArray(),
            'data' => $chartData->pluck('daily_profit_loss')->toArray(),
        ];

//dd($chartDataFormatted);
        return view('livewire.dashboard', compact('aggregates', 'chartDataFormatted'));
    }

}
