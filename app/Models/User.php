<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'client_uuid',
        'client_name',
        'client_email',
        'client_password',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'client_password'
    ];

    public function getAuthPassword()
    {
        return $this->client_password;
    }


    public function getAuthIdentifierName()
    {
        return 'client_email';
    }
}
