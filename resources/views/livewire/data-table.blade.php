<div>
    <div>
        <button wire:click="exportCsv" class="btn btn-sm btn-primary">CSV</button>
        <button wire:click="exportXlsx" class="btn btn-sm btn-success">XLSX</button>
        <button wire:click="exportPdf" class="btn btn-sm btn-danger">PDF</button>
    </div>
    <br>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>symbol</th>
            <th>side</th>
            <th>profit_loss</th>
            <th>volume</th>
            <th>open_price</th>
            <th>close_price</th>
            <th>order_uuid</th>
            <th>open_time</th>
            <th>close_time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->symbol }}</td>
                <td>{{ $order->side }}</td>
                <td>{{ $order->profit_loss }}</td>
                <td>{{ $order->volume }}</td>
                <td>{{ $order->open_price }}</td>
                <td>{{ $order->close_price }}</td>
                <td>{{ $order->order_uuid }}</td>
                <td>{{ $order->open_time }}</td>
                <td>{{ $order->close_time }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>

            <select wire:model.lazy="perPage" id="perPage" wire:change="updatePerPage" class="form-control d-inline-block w-auto">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="300">300</option>
                <option value="400">400</option>
                <option value="500">500</option>
            </select>

        </div>

        {{ $orders->onEachSide(5)->links(data : ['scrollTo' => false]) }}

    </div>
</div>
<script>
    $(document).ready(function() {
        // Prevent automatic scroll when pagination is clicked
        $('ul.pagination').on('click', 'a', function(e) {
            e.preventDefault();

            // Perform the page change via AJAX or Livewire, as needed.
            let url = $(this).attr('href');
            if (url) {
                window.location.href = url;  // Or use Livewire's method to update the page
            }
        });
    });
</script>

