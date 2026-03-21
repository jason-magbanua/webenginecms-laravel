<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class RegisterAccount extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_REGISTER_ACCOUNT';
    protected $primaryKey = 'reg_id';
    public $timestamps = false;

    protected $fillable = [
        'reg_account', 'reg_password', 'reg_email',
        'reg_token', 'reg_date', 'reg_expire',
    ];

    protected $casts = [
        'reg_date'   => 'datetime',
        'reg_expire' => 'datetime',
    ];
}
