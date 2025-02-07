<div>
    <!-- Filters -->
    <div class="row my-3">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="">
                    <div class="d-flex justify-content-end gap-2 align-items-center">
                        <div class="form-group mr-2">
                            <select id="filter1" wire:model="filters.filter1" class="form-control">
                                <option value="">Select Option</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-2">
                            <input type="text" id="dateRange" wire:model="filters.date_range" class="form-control"
                                placeholder="Select Date Range">
                        </div>

                        <button wire:click="applyFilters" id="applyBtn" class="btn btn-primary ml-2">Apply</button>
                        <button wire:click="resetFilters" class="btn btn-secondary ml-2">Reset</button>

                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 mb-4">

        <!-- Card 1: Total Net P&L -->
        <div class="col">
            <div class="card card-custom p-3 border-primary shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Net P&amp;L</h6>
                        <h4>$ {{ $aggregates->total_pnl }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-chart-line text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Win Ratio -->
        <div class="col">
            <div class="card card-custom p-3 border-success shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total win ratio</h6>
                        <h4 class="text-success">
                            {{ number_format(($aggregates->total_win_trades / $aggregates->total_trades) * 100, 2) }} %
                        </h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-trophy text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Trades -->
        <div class="col">
            <div class="card card-custom p-3 border-info shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total trades</h6>
                        <h4>{{ $aggregates->total_trades }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-cogs text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Loss Trades -->
        <div class="col">
            <div class="card card-custom p-3 border-danger shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total loss trades</h6>
                        <h4 class="text-danger">{{ $aggregates->total_loss_trades }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-times-circle text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Gross Profit -->
        <div class="col">
            <div class="card card-custom p-3 border-success shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total gross profit</h6>
                        <h4 class="text-success">$ {{ $aggregates->total_profit }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-arrow-up text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6: Total Gross Loss -->
        <div class="col">
            <div class="card card-custom p-3 border-danger shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total gross loss</h6>
                        <h4 class="text-danger">$ {{ $aggregates->total_loss }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-arrow-down text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 7: Max Loss -->
        <div class="col">
            <div class="card card-custom p-3 border-warning shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Max loss</h6>
                        <h4>$ {{ number_format($aggregates->max_loss, 2) }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-minus-circle text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 8: Max Win -->
        <div class="col">
            <div class="card card-custom p-3 border-info shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Max win</h6>
                        <h4>${{ number_format($aggregates->max_win, 2) }}</h4>
                    </div>
                    <div class="icon-box">
                        <i class="fas fa-plus-circle text-info fs-3"></i>
                    </div>
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
    $(document).ready(function() {
        $('#dateRange').daterangepicker({
            autoUpdateInput: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            let dateRange = picker.startDate.format('YYYY-MM-DD') + '' + picker.endDate.format(
                'YYYY-MM-DD');

            console.log("Selected Date Range:", dateRange);

            $('#dateRange').val(dateRange).trigger('input');

            Livewire.dispatch('dateRangeUpdated', {
                value: dateRange
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
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
