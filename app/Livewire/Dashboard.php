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
        dd($this->filters);
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
        $baseQuery = Position::query()
            ->join('user_exchange_details', 'user_exchange_details.user_uuid', '=', 'positions.user_uuid');

        if (!empty($this->filters['filter1'])) {
            $baseQuery->where('user_exchange_details.account_nickname', $this->filters['filter1']);
        }
        $chartQuery = Position::selectRaw('DATE(close_time) as date, SUM(profit_loss) as daily_profit_loss')
            ->groupBy(DB::raw('DATE(close_time)'))
            ->orderBy('date', 'ASC');

        if (!empty($this->filters['date_range'])) {
            dd($this->filters['date_range']);
            $dates = explode('to', $this->filters['date_range']);
            $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
            $endDate = Carbon::parse(trim($dates[1]))->endOfDay();
            $chartQuery->whereBetween('close_time', [$startDate, $endDate]);
        } else {
            $chartQuery->where('close_time', '>=', Carbon::now()->subDays(8));
        }

        $chartData = $chartQuery->get();

        $chartDataFormatted = [
            'labels' => $chartData->pluck('date')->toArray(),
            'data' => $chartData->pluck('daily_profit_loss')->toArray(),
        ];


        $aggregates = $baseQuery->select(
            DB::raw('SUM(positions.profit_loss) as total_pnl'),
            DB::raw('COUNT(CASE WHEN positions.profit_loss >= 0 THEN 1 END) as total_win_trades'),
            DB::raw('COUNT(CASE WHEN positions.profit_loss < 0 THEN 1 END) as total_loss_trades'),
            DB::raw('SUM(CASE WHEN positions.profit_loss >= 0 THEN positions.profit_loss ELSE 0 END) as total_profit'),
            DB::raw('SUM(CASE WHEN positions.profit_loss < 0 THEN positions.profit_loss ELSE 0 END) as total_loss'),
            DB::raw('MIN(CASE WHEN positions.profit_loss < 0 THEN CAST(positions.profit_loss AS DECIMAL(25,8)) END) AS max_loss'),
            DB::raw('MAX(CASE WHEN positions.profit_loss > 0 THEN CAST(positions.profit_loss AS DECIMAL(25,8)) END) AS max_win'),
            DB::raw('AVG(positions.profit_loss) AS avg_profit_loss'),
            DB::raw('SUM(positions.usd_amount) AS total_usd_amount'),
            DB::raw('COUNT(*) as total_trades')
        )->first();


        return view('livewire.dashboard', compact('aggregates', 'chartDataFormatted'));
    }




}
