<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class CreditConfig extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_CREDITS_CONFIG';
    protected $primaryKey = 'config_id';
    public $timestamps = false;

    protected $fillable = [
        'config_title', 'config_database', 'config_table',
        'config_credits_col', 'config_user_col', 'config_user_col_id',
        'config_checkonline', 'config_display',
    ];
}
