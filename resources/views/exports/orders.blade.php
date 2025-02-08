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
            table-layout: fixed; /* Ensures fixed column width */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center; /* Align text */
            word-wrap: break-word; /* Prevents text overflow */
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            font-size: 12px; /* Reduce font size if needed */
        }
    </style>

</head>
<body>
<h3>Orders Report</h3>
<table>
    <thead>
    <tr>
        <th style="width: 10%;">Index</th>
        <th style="width: 10%;">Symbol</th>
        <th style="width: 8%;">Side</th>
        <th style="width: 12%;">Profit/Loss</th>
        <th style="width: 10%;">Volume</th>
        <th style="width: 15%;">Open Price</th>
        <th style="width: 15%;">Close Price</th>
        <th style="width: 20%;">Order UUID</th>
        <th style="width: 15%;">Open Time</th>
        <th style="width: 15%;">Close Time</th>

    </tr>
    </thead>
    <tbody>
    @foreach($allData as $index => $order)
        <tr>
            <td>{{ $index + 1 }}</td> <!-- Index Number -->
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
</body>
</html>
