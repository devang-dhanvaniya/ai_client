<!DOCTYPE html>
<html>
<head>
    <title>Orders Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 14px;
        }
        td {
            font-size: 12px;
        }
    </style>

</head>
<body>
<h3>Orders Report</h3>
<table>
    <thead>
    <tr>

        <th style="width: 15%;">Symbol</th>
        <th style="width: 9%;">Side</th>
        <th style="width: 12%;">PnL</th>
        <th style="width: 21%;">Account Name</th>
        <th style="width: 12%;">Volume</th>
        <th style="width: 15%;">Open Price</th>
        <th style="width: 15%;">Close Price</th>
        <th style="width: 16%;">Order ID</th>
        <th style="width: 18%;">Open Time</th>
        <th style="width: 18%;">Close Time</th>

    </tr>
    </thead>
    <tbody>
    @foreach($allData as $index => $order)
        <tr>
            <td>{{ $order->symbol }}</td>
            <td>{{ $order->side }}</td>
            <td>{{ $order->profit_loss }}</td>
            <td>{{ $order->walletConfig->account_nickname }}</td>
            <td>{{ number_format($order->volume,2) }}</td>
            <td>{{ (float) $order->open_price }}</td>
            <td>{{ (float) $order->close_price }}</td>
            <td>{{ $order->order_uuid }}</td>
            <td>{{ $order->open_time }}</td>
            <td>{{ $order->close_time }}</td>
        </tr>
    @endforeach

    </tbody>
</table>
</body>
</html>
