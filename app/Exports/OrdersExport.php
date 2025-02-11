<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }


    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                'symbol' => $order->symbol,
                'side' => $order->side,
                'profit_loss' => $order->profit_loss,
                'account_name' => $order->walletConfig->account_nickname,
                'volume' => $order->volume,
                'open_price' => $order->open_price,
                'close_price' => $order->close_price,
                'order_uuid' => $order->order_uuid,
                'open_time' => $order->open_time,
                'close_time' => $order->close_time,
            ];
        });
    }

    public function headings(): array
    {
        return ['Symbol', 'Side', 'Profit/Loss', 'Account Name', 'Volume', 'Open Price', 'Close Price', 'Order ID', 'Open Time', 'Close Time'];
    }
}
