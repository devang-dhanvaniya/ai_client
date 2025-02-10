<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DateFilterComponent extends Component
{
    public $filterData = [
        'InitiateDate' => null,
        'FinalizeDate' => null
    ];
    public $defaultInitiateDate;
    public $defaultFinalizeDate;
    protected $listeners = ['dateRangeUpdated'];
    public function mount()
    {
        $this->defaultInitiateDate = now()->startOfDay()->toDateTimeString();
        $this->defaultFinalizeDate = now()->endOfDay()->toDateTimeString();

        if (is_null($this->filterData['InitiateDate']) || is_null($this->filterData['FinalizeDate'])) {
            $this->filterData['InitiateDate'] = $this->defaultInitiateDate;
            $this->filterData['FinalizeDate'] = $this->defaultFinalizeDate;
        }
        Log::info($this->filterData);
    }
    public function dateRangeUpdated($startDate, $endDate)
    {
        $this->filterData['InitiateDate'] = $startDate;
        $this->filterData['FinalizeDate'] = $endDate;
    }
    public function updateDateRange($startDate, $endDate)
    {
        $this->filterData['InitiateDate'] = $startDate;
        $this->filterData['FinalizeDate'] = $endDate;
    }
    public function resetDateRange()
    {
        $this->filterData['InitiateDate'] = $this->defaultInitiateDate;
        $this->filterData['FinalizeDate'] = $this->defaultFinalizeDate;
    }
}
