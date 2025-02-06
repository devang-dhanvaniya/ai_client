<div class="container-fluid">
    <!-- Filters -->
    <br>
    <div class="row mb-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="">
                    <div class="d-flex justify-content-left gap-2 align-items-center">
                        <div class="form-group mr-2">
                            <select id="filter1" wire:model="filters.filter1" class="form-control">
                                <option value="">Select Option</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-2">
                            <input type="text" id="dateRange" wire:model="filters.date_range" class="form-control" placeholder="Select Date Range" >
                        </div>

                        <button wire:click="applyFilters" id="applyBtn" class="btn btn-primary ml-2">Apply</button>
                        <button wire:click="resetFilters" class="btn btn-secondary ml-2">Reset</button>

                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <!-- Card 1: Total Net P&L -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-chart-line"></i> Total net p&l</h6>
                    <h4>${{ $aggregates->total_pnl }}</h4>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Win Ratio -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-trophy"></i> Total win ratio</h6>
                    <h4>{{ number_format($aggregates->total_win_trades / $aggregates->total_trades * 100, 2) }}%</h4>

                </div>
            </div>
        </div>

        <!-- Card 3: Total Trades -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-cogs"></i> Total trades</h6>
                    <h4>{{ $aggregates->total_trades }}</h4>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Loss Trades -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-times-circle"></i> Total loss trades</h6>
                    <h4>{{ $aggregates->total_loss_trades }}</h4>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Gross Profit -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-arrow-up"></i> Total gross profit</h6>
                    <h4>${{ $aggregates->total_profit }}</h4>
                </div>
            </div>
        </div>

        <!-- Card 6: Total Gross Loss -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-arrow-down"></i> Total gross loss</h6>
                    <h4>${{ $aggregates->total_loss }}</h4>
                </div>
            </div>
        </div>

        <!-- Card 7: Max Loss -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-minus-circle"></i> Max loss</h6>
                    <h4>${{ number_format($aggregates->max_loss, 2) }}</h4>

                </div>
            </div>
        </div>

        <!-- Card 8: Max Win -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-plus-circle"></i> Max win</h6>
                    <h4>${{ number_format($aggregates->max_win ,2) }}</h4>
                </div>
            </div>
        </div>
    </div>



    <!-- Chart -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5> Chart (Last 7 Days)</h5>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {
        $('#dateRange').daterangepicker({
            autoUpdateInput: true,
            locale: { format: 'YYYY-MM-DD' }
        }).on('apply.daterangepicker', function (ev, picker) {
            let dateRange = picker.startDate.format('YYYY-MM-DD') + '' + picker.endDate.format('YYYY-MM-DD');

            console.log("Selected Date Range:", dateRange);

            $('#dateRange').val(dateRange).trigger('input');

            Livewire.dispatch('dateRangeUpdated', { value: dateRange });
        });
    });






</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chartData = @json($chartDataFormatted);
        const labels = chartData.labels;
        const data = chartData.data;
        const ctx = document.getElementById("salesChart").getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Amount",
                    data: data,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    });
</script>


