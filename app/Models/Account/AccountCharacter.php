<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class AccountCharacter extends Model
{
    protected $connection = 'memuonline';
    protected $table = 'AccountCharacter';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['Id', 'GameIDC', 'WarehouseExpansion', 'SecCode'];
}
