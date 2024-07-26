<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;
    use DefaultDatetimeFormat;

    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'token',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [

        'password' => 'hashed',
    ];


}
