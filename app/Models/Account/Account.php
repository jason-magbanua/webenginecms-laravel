<?php

namespace App\Models\Account;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $connection = 'memuonline';
    protected $table = 'MEMB_INFO';
    protected $primaryKey = 'memb_guid';
    public $timestamps = false;

    protected $fillable = [
        'memb___id', 'memb__pwd', 'memb_name', 'mail_addr',
        'bloc_code', 'ctl_cod', 'AccountLevel', 'AccountExpireDate',
    ];

    protected $hidden = ['memb__pwd'];

    public function getAuthIdentifierName(): string
    {
        return 'memb___id';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->memb___id;
    }

    public function getAuthPassword(): string
    {
        return $this->memb__pwd;
    }

    public function getAuthPasswordName(): string
    {
        return 'memb__pwd';
    }

    public function accountCharacters()
    {
        return $this->hasMany(AccountCharacter::class, 'Id', 'memb___id');
    }

    public function status()
    {
        return $this->hasOne(MemberStatus::class, 'memb___id', 'memb___id');
    }
}
