<?php

namespace App\Auth;

use App\Models\Account\Account;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class MuOnlineUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        return Account::where('memb___id', $identifier)->first();
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        //
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials['memb___id'])) {
            return null;
        }

        return Account::where('memb___id', $credentials['memb___id'])->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return $user->getAuthPassword() === $credentials['password'];
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Plain text — no rehash
    }
}
