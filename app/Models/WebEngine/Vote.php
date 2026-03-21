<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_VOTES';
    protected $primaryKey = 'vote_id';
    public $timestamps = false;

    protected $fillable = [
        'vote_account', 'vote_site_id', 'vote_date', 'vote_ip',
    ];

    protected $casts = [
        'vote_date' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(VoteSite::class, 'vote_site_id', 'site_id');
    }
}
