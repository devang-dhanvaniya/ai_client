

<div class="container mt-4">
    <div class="my-3 d-flex justify-content-between align-items-center gap-2">
        <div>
            <button wire:click="exportCsv" class="btn btn-sm btn-primary rounded-pill shadow">CSV</button>
            <button wire:click="exportXlsx" class="btn btn-sm btn-success rounded-pill shadow">XLSX</button>
            <button wire:click="exportPdf" class="btn btn-sm btn-danger rounded-pill shadow">PDF</button>
        </div>
        <div class="d-flex gap-1">
            <div class="form-group mr-2">
                <select id="filterType" wire:model="selectedAccount" class="form-control">
                    <option value="">Select Account</option>
                    @foreach ($allAccounts as $option)
                        <option value="{{ $option['user_exchange_uuid'] }}">{{ $option['account_nickname']."(".$option['account_login'].")" }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <input type="text" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                       id="dateRangePicker" class="form-control" placeholder="Select Date Range" autocomplete="off">
            </div>
            <button class="btn btn-primary ml-2" id="applyButton" wire:click="getPositionDate">
                Apply
            </button>
            <button wire:click="resetHistoryFilters" class="btn btn-secondary ml-2" id="resetFilterData">Reset</button>
        </div>

    </div>

    <table id="dataTable" class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="table-primary fontSize14">
            <tr>
                <th wire:click="setSortFunctionality('symbol')">
                    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
                        Symbol
                        <div>
                            @if ($sortByColumn == 'symbol')
                                @if ($sortDirection == 'ASC')
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                                @else
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down"></i>
                                @endif
                            @else
                                <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                            @endif
                        </div>
                    </div>
                </th>
                <th wire:click="setSortFunctionality('side')">
                    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
                        Side
                        <div>
                            @if ($sortByColumn == 'side')
                                @if ($sortDirection == 'ASC')
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                                @else
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down"></i>
                                @endif
                            @else
                                <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                            @endif
                        </div>
                    </div>
                </th>
                <th wire:click="setSortFunctionality('profit_loss')">
                    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
                        Profit/Loss
                        <div>
                            @if ($sortByColumn == 'profit_loss')
                                @if ($sortDirection == 'ASC')
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                                @else
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down"></i>
                                @endif
                            @else
                                <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                            @endif
                        </div>
                    </div>
                </th>
                <th>Account Name</th>
                <th>Volume</th>
                <th>Open Price</th>
                <th>Close Price</th>
                <th>Order ID</th>
                <th wire:click="setSortFunctionality('open_time')">
                    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
                        Open Time
                        <div>
                            @if ($sortByColumn == 'open_time')
                                @if ($sortDirection == 'ASC')
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                                @else
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down"></i>
                                @endif
                            @else
                                <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                            @endif
                        </div>
                    </div>
                </th>
                <th wire:click="setSortFunctionality('close_time')">
                    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
                        Close Time
                        <div>
                            @if ($sortByColumn == 'close_time')
                                @if ($sortDirection == 'ASC')
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                                @else
                                    <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                    <i class="cursor-pointer d-block fa-solid fa-angle-down"></i>
                                @endif
                            @else
                                <i class="cursor-pointer d-block fa-solid fa-angle-up" style="opacity: 0.5;"></i>
                                <i class="cursor-pointer d-block fa-solid fa-angle-down" style="opacity: 0.5;"></i>
                            @endif
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="fontSize12">
        @foreach ($positions as $order)
            <tr>
                <td>{{ $order['symbol'] }}</td>
                <td>
                <span class="">
                    @if ($order['side'] === 'buy')
                        <i class="bi bi-arrow-up-right text-success"></i> Buy
                    @elseif ($order['side'] === 'sell')
                        <i class="bi bi-arrow-down-right text-danger"></i> Sell
                    @else
                        {{ ucfirst($order['side']) }}
                    @endif
                </span>
                </td>
                <td>
                <span class="{{ $order['profit_loss'] >= 0 ? 'text-success' : 'text-danger' }}">
                    ${{ $order['profit_loss'] }}
                </span>
                </td>
                <td>{{ $order->walletConfig->account_nickname }}</td>
                <td>{{ number_format($order['volume'],2) }}</td>
                <td>{{ (float) $order['open_price'] }}</td>
                <td>{{ (float) $order['close_price'] }}</td>
                <td><code>{{ $order['order_uuid'] }}</code></td>
                <td>{{ $order['open_time'] }}</td>
                <td>{{ $order['close_time'] }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <select wire:model.lazy="perPage" class="form-select w-auto shadow-sm">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="300">300</option>
            <option value="400">400</option>
            <option value="500">500</option>
        </select>
        {{ $positions ->links() }}
    </div>
</div>

<script>
    let dataTable;

    function initializeDataTable() {
        dataTable = $('#dataTable').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            searching: false,
            paging: false,
            info: false,
            ordering: false,
        });
    }

    $(document).ready(function() {
        initializeDataTable();
    });

    let startDate = null;
    let endDate = null;
    defaultStartDate = new Date(@json($defaultInitiateDate));
    defaultEndDate = new Date(@json($defaultFinalizeDate));

    let datePicker = flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "d-m-Y",
        defaultDate: [defaultStartDate, defaultEndDate],
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

    storedStartDate = @json(session('historyStartDate'));
    storedEndDate = @json(session('historyEndDate'));

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
        if (startDate && endDate) {
            @this.set('filterData.InitiateDate', startDate);
            @this.set('filterData.FinalizeDate', endDate);

            @this.call('storeHistoryDates', startDate, endDate);
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
</script>
