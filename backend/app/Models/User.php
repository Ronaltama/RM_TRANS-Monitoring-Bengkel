<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, GeneratesCustomId, HasApiTokens;

    protected static function idPrefix(): string
    {
        return 'USR';
    }

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'username',
        'name',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
