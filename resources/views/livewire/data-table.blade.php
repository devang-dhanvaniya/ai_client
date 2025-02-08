

<div class="container mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-center gap-2">
        <h5 class="mb-0 fs-4">Data List</h5>
        <div>
            <div class="form-group mr-2">
                <input type="text" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                       id="dateRangePicker" class="form-control" placeholder="Select Date Range" autocomplete="off">
            </div>
            <button class="btn btn-primary ml-2" id="applyButton" wire:click="getPositionDate">
                Apply
            </button>
        </div>
        <div>
            <button wire:click="exportCsv" class="btn btn-sm btn-primary rounded-pill shadow">CSV</button>
            <button wire:click="exportXlsx" class="btn btn-sm btn-success rounded-pill shadow">XLSX</button>
            <button wire:click="exportPdf" class="btn btn-sm btn-danger rounded-pill shadow">PDF</button>
        </div>
    </div>

    <table id="dataTable" class="table table-striped table-hover table-bordered align-middle shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>Symbol</th>
                <th>Side</th>
                <th>Profit/Loss</th>
                <th>Volume</th>
                <th>Open Price</th>
                <th>Close Price</th>
                <th>Order UUID</th>
                <th>Open Time</th>
                <th>Close Time</th>
            </tr>
        </thead>
        <tbody>
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
                    {{ $order['profit_loss'] }}
                </span>
                </td>
                <td>{{ $order['volume'] }}</td>
                <td>{{ $order['open_price'] }}</td>
                <td>{{ $order['close_price'] }}</td>
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
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: false,
            searching: false,
            paging: false,
            info: false
        });
    });

    let startDate = null;
    let endDate = null;

    function getLast7Days() {
        const today = new Date();
        const last7Days = new Date();
        last7Days.setDate(today.getDate() - 8);
        return [last7Days, today];
    }

    const datePicker = flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "d-m-Y",
        defaultDate: null,
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 00:00:00";
                endDate = flatpickr.formatDate(selectedDates[1], "Y-m-d") + " 23:59:59";
            }
        }
    });

    document.getElementById("applyButton").addEventListener("click", function() {
        if (startDate && endDate) {
            Livewire.emit('updateDateRange', startDate, endDate);
            Livewire.emit('getPositionDate');
        } else {
            console.log("Date range not selected yet!");
        }
    });



    document.getElementById('applyButton').addEventListener('click', function() {
        if (startDate && endDate) {
        @this.set('filterData.InitiateDate', startDate);
        @this.set('filterData.FinalizeDate', endDate);
        }
    });

</script>
