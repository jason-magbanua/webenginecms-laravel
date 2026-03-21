<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class MemberStatus extends Model
{
    protected $connection = 'memuonline';
    protected $table = 'MEMB_STAT';
    protected $primaryKey = 'memb___id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['memb___id', 'ConnectStat', 'ServerName', 'IP'];
}
