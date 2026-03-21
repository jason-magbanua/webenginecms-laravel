<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_BANS';
    protected $primaryKey = 'ban_id';
    public $timestamps = false;

    protected $fillable = [
        'ban_account', 'ban_reason', 'ban_type',
        'ban_date', 'ban_expire', 'ban_by',
    ];

    protected $casts = [
        'ban_date'   => 'datetime',
        'ban_expire' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('ban_expire')
              ->orWhere('ban_expire', '>', now());
        });
    }
}
