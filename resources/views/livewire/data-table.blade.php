
<div>
    <div class="row my-3">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="mb-3 d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <button wire:click="exportCsv" class="btn btn-sm btn-primary rounded-pill shadow">CSV</button>
                        <button wire:click="exportXlsx" class="btn btn-sm btn-success rounded-pill shadow">XLSX</button>
                        <button wire:click="exportPdf" class="btn btn-sm btn-danger rounded-pill shadow">PDF</button>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="form-group mr-2">
                            <select id="filterType" wire:model="selectedAccount" class="form-control">
                                <option value="">Select Account</option>
                                @foreach ($allAccounts as $account)
                                    <option value="{{ $account['user_exchange_uuid'] }}">{{ $account['account_nickname']."(".$account['account_login'].")" }}</option>
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
            </div>
        </div>

        <table id="dataTable" class="table table-striped table-hover table-bordered align-middle shadow-sm">
            <thead class="table-primary fontSize14 align-middle">
                <tr>
                    <x-sortable-column column="symbol" label="Symbol" :sortByColumn="$sortByColumn" :sortDirection="$sortDirection" />
                    <x-sortable-column column="side" label="Side" :sortByColumn="$sortByColumn" :sortDirection="$sortDirection" />
                    <x-sortable-column column="profit_loss" label="Profit/Loss" :sortByColumn="$sortByColumn" :sortDirection="$sortDirection" />
                    <th>Account Name</th>
                    <th>Volume</th>
                    <th>Open Price</th>
                    <th>Close Price</th>
                    <th>Order ID</th>
                    <x-sortable-column column="open_time" label="Open Time" :sortByColumn="$sortByColumn" :sortDirection="$sortDirection" />
                    <x-sortable-column column="close_time" label="Close Time" :sortByColumn="$sortByColumn" :sortDirection="$sortDirection" />
                </tr>
            </thead>
            <tbody class="fontSize12">
                @if (!empty($positions) && $positions->count() > 0)
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
                @endif
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
            {{ $positions->links(data: ['scrollTo' => false]) ?? '' }}
        </div>
    </div>
</div>
<script>
    dataTable;

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

        // if ($('#dataTable tbody tr').length > 0) {
        //     initializeDataTable();
        // }
    });

    startDate = null;
    endDate = null;
    defaultStartDate = new Date(@json($defaultInitiateDate));
    defaultEndDate = new Date(@json($defaultFinalizeDate));

    datePicker = flatpickr("#dateRangePicker", {
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

        setTimeout(function() {
            window.location.reload();
        }, 100);
    });

</script>
