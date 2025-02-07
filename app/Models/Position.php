<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $primaryKey = 'position_id';

    protected $table = 'tbl_positions';

    protected $connection = 'forex_db';

    public $timestamps = false;

    protected $fillable = [
        'position_uuid',
        'order_uuid',
        'strategy_uuid',
        'parent_position_uuid',
        'user_uuid',
        'wallet_uuid',
        'base_currency',
        'quote_currency',
        'symbol',
        'original_symbol',
        'exchange_name',
        'user_exchange_uuid',
        'volume',
        'current_volume',
        'executed_volume',
        'base_amount',
        'executed_base_amount',
        'quote_amount',
        'executed_quote_amount',
        'usd_amount',
        'usd_amount_after_leverage',
        'fees',
        'current_price',
        'open_price',
        'close_price',
        'take_profit',
        'stop_loss',
        'side',
        'profit_loss',
        'open_time',
        'close_time',
        'position_status',
        'trailing_stop_loss',
        'client_id',
        'platform',
        'comment',
        'is_connect_order',
        'account_currency_exchange_rate',
        'is_auto_exit',
        'is_manual_trade',
        'created_at',
        'updated_at',
        'open_by',
        'modified_by',
        'close_by',
        'is_ai_generated',
        'timeframe'
    ];
}
