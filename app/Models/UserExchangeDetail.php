<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExchangeDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'tbl_user_exchange_details';

    protected $connection = 'forex_db';

    public $timestamps = false;

    protected $fillable = [
        'user_exchange_uuid',
        'user_uuid',
        'exchange_name',
        'account_id',
        'access_token',
        'account_nickname',
        'is_active',
        'region',
        'created_at',
        'deleted_at'
    ];
    public function clients(): BelongsToMany
    {
        return $this->setConnection('mysql')->belongsToMany(User::class, 'tbl_client_exchange','user_exchange_id', 'client_id');
    }
}
