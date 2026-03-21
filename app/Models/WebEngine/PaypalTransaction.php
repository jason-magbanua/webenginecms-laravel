<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class PaypalTransaction extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_PAYPAL_TRANSACTIONS';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_account', 'transaction_amount', 'transaction_currency',
        'transaction_status', 'transaction_paypal_id', 'transaction_date',
        'transaction_credits',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];
}
