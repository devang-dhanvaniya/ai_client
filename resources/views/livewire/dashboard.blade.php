<div>
    <!-- Filters -->
    <div class="row my-3" style="margin-top: 2rem !important;">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="">
                    <div class="d-flex justify-content-end gap-2 align-items-center">
                        <div class="form-group mr-2">
                            <select id="filterType" wire:model="selectedFilter" class="form-control">
                                <option value="">Select Account</option>
                                @foreach ($options as $option)
                                <option value="{{ $option['user_exchange_uuid'] }}">{{ $option['account_nickname']."(".$option['account_login'].")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="text" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                                id="dateRangePicker" class="form-control" placeholder="Select Date Range" autocomplete="off">
                        </div>
                        <button class="btn btn-primary ml-2" id="applyButton" wire:click="getDashboardData">
                            Apply
                        </button>
                        <button wire:click="resetFilters" class="btn btn-secondary ml-2" id="resetFilterData">Reset</button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 mb-4">
        <div class="col">
            <div class="card card-custom p-3 border-primary shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Net P&amp;L</h6>
                        <h5 class="{{ $aggregates['total_pnl'] >= 0 ? 'text-success' : 'text-danger' }}">$ {{ $aggregates['total_pnl'] }}</h5>

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
                        <h5 class="text-success">
                            {{ $aggregates['total_win_ratio'] }} %
                        </h5>
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
                        <h5>{{ $aggregates['total_trades'] }}</h5>
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
                        <h5 class="text-danger">{{ $aggregates['total_loss_trades'] }}</h5>
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
                        <h5 class="text-success">$ {{ $aggregates['total_profit'] }}</h5>
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
                        <h5 class="text-danger">$ {{ $aggregates['total_loss'] }}</h5>
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
                        <h5>$ {{ number_format($aggregates['max_loss'], 2) }}</h5>
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
                        <h5>$ {{ number_format($aggregates['max_win'], 2) }}</h5>
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
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div id="sales_chart_container" class="position-relative">
                    <div id="sales_chart_loader" class="text-center" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="sales_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-start gap-4 overflow-x-auto" id="calendar_container"></div>
    </div>

</div>
@livewireScripts
<script>
     startDate = null;
     endDate = null;

     defaultStartDate = new Date(@json($defaultInitiateDate));
     defaultEndDate = new Date(@json($defaultFinalizeDate));

     today = new Date();
     formattedToday = flatpickr.formatDate(today, "d-m-Y");

     datePicker = flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "d-m-Y",
        defaultDate: [defaultStartDate, defaultEndDate],
        maxDate: formattedToday,
        onChange: function (selectedDates) {
             if (selectedDates.length === 2) {
                 startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 00:00:00";
                 endDate = flatpickr.formatDate(selectedDates[1], "Y-m-d") + " 23:59:59";
                 document.getElementById("dateRangePicker").value =
                     flatpickr.formatDate(selectedDates[0], "d-m-Y") + " to " +
                     flatpickr.formatDate(selectedDates[1], "d-m-Y");
             } else if (selectedDates.length === 1) {
                 startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 00:00:00";
                 endDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 23:59:59";
             }
         },
    });

    storedStartDate = @json(session('dashboardStartDate'));
    storedEndDate = @json(session('dashboardEndDate'));

    if (storedStartDate && storedEndDate) {
         const startDateFormatted = flatpickr.formatDate(new Date(storedStartDate), "d-m-Y");
         const endDateFormatted = flatpickr.formatDate(new Date(storedEndDate), "d-m-Y");
         datePicker.setDate([new Date(storedStartDate), new Date(storedEndDate)], true);
         document.getElementById("dateRangePicker").value = startDateFormatted + " to " + endDateFormatted;
    } else {
         document.getElementById("dateRangePicker").value =
             flatpickr.formatDate(defaultStartDate, "d-m-Y") + " to " +
             flatpickr.formatDate(defaultEndDate, "d-m-Y");
    }

    document.getElementById('applyButton').addEventListener('click', function() {
        document.getElementById('sales_chart_loader').style.display = 'block';
        if (startDate && endDate) {
            @this.set('filterData.InitiateDate', startDate);
            @this.set('filterData.FinalizeDate', endDate);

            @this.call('storeDashboardDates', startDate, endDate);
        }
    });
    document.getElementById('resetFilterData').addEventListener('click', function() {
        datePicker.setDate([defaultStartDate, defaultEndDate]);
        startDate = flatpickr.formatDate(defaultStartDate, "Y-m-d") + " 00:00:00";
        endDate = flatpickr.formatDate(defaultEndDate, "Y-m-d") + " 23:59:59";

        @this.set('filterData.InitiateDate', startDate);
        @this.set('filterData.FinalizeDate', endDate);

        document.getElementById("dateRangePicker").value =
            flatpickr.formatDate(defaultStartDate, "d-m-Y") + " to " +
            flatpickr.formatDate(defaultEndDate, "d-m-Y");
    });

    document.addEventListener('click', function(event) {
         const toolbarMenu = document.querySelector('.apexcharts-menu');
         const toolbarIcon = document.querySelector('.apexcharts-menu-icon');
         if (toolbarMenu && toolbarMenu.classList.contains('apexcharts-menu-open') &&
             !toolbarMenu.contains(event.target) && !toolbarIcon.contains(event.target)) {
             toolbarIcon.click();
         }
    });
    window.Livewire.on("chartDatas", (datas) => {
        let data = datas?.chartDatas?.data || [];
        let labels = datas?.chartDatas?.labels || [];
        let colors = datas?.chartDatas?.colors || [];

        document.getElementById('sales_chart_loader').style.display = 'none';
        renderChart("sales_chart", data, labels, colors);
        window.dispatchEvent(new Event('resize'));
    });

    function renderChart(chartElementId, chartData, chartlabels, chartColors) {
        chartData = chartData.map(value => parseFloat(value));
        const existingChart = ApexCharts.getChartByID("sales_chart");

        if (existingChart) {
            existingChart.destroy();
        }
        var options = {
            series: [{
                name: 'Trades',
                data: chartData || []
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                },
                id: chartElementId
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    columnWidth: 50,
                    distributed: true,
                    borderRadius: 10,
                    dataLabels: {
                        total: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                fontWeight: 600,
                                color: 'black'
                            }
                        }
                    }
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function(value) {
                    return `$${value}`;
                },
                enabledOnSeries: [1]
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return `$${value}`;
                    }
                },
            },
            states: {
                normal: {
                    filter: {
                        type: 'none'
                    }
                },
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            xaxis: {
                categories: chartlabels || [],
            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Profit', 'Loss'],
                markers: {
                    fillColors: ['#82ca9d', '#ff96a0']
                }
            },
            fill: {
                opacity: 1,
            },
            colors: chartColors || []
        };
        var chart = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
        chart.render().then(() => {
            getCustomCalendar(chartlabels ,chartData);
        });
    }

     function getCustomCalendar(dates, chartData) {
         let calendarContainer = document.getElementById('calendar_container');

         if (!calendarContainer) {
             //console.log("Calendar container not found.");
             return;
         }

         calendarContainer.innerHTML = "";

         if (!Array.isArray(dates) || dates.length === 0) {
             //console.log("Invalid dates array:", dates);
             return;
         }

         let uniqueMonths = [...new Set(dates.map(date => date.substring(0, 7)))];

         uniqueMonths.forEach((month) => {
             let [year, monthNumber] = month.split("-");
             let monthName = new Date(year, monthNumber - 1).toLocaleString("default", { month: "long" });

             // Create Month Container
             let monthWrapper = document.createElement("div");
             monthWrapper.classList.add("month-wrapper");

             let titleEl = document.createElement("h3");
             titleEl.innerText = `${monthName} - ${year}`;
             titleEl.classList.add("month-title");
             monthWrapper.appendChild(titleEl);

             let calendarGrid = document.createElement("div");
             calendarGrid.classList.add("calendar-grid");

             // Get the first day and number of days in the month
             let firstDay = new Date(year, monthNumber - 1, 1).getDay();
             let daysInMonth = new Date(year, monthNumber, 0).getDate();

             // Add Empty Spaces for First Week Offset
             for (let i = 0; i < firstDay; i++) {
                 let emptyCell = document.createElement("div");
                 emptyCell.classList.add("calendar-cell", "empty-cell");
                 calendarGrid.appendChild(emptyCell);
             }

             // Generate Days
             for (let day = 1; day <= daysInMonth; day++) {
                 let dateStr = `${year}-${monthNumber.padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                 let dayCell = document.createElement("div");
                 dayCell.classList.add("calendar-cell");

                 let dateIndex = dates.indexOf(dateStr);
                 if (dateIndex !== -1) {
                     let value = parseFloat(chartData[dateIndex]);
                     dayCell.style.backgroundColor = value < 0 ? "#ff4d4d" : "#28a745";
                     dayCell.style.color = "white";

                     // Add tooltip with chartData value
                     dayCell.setAttribute("data-tooltip", `$${value}`);
                 }

                 dayCell.innerText = day;
                 calendarGrid.appendChild(dayCell);
             }

             monthWrapper.appendChild(calendarGrid);
             calendarContainer.appendChild(monthWrapper);
         });
     }

</script>
