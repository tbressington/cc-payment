<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth extends Model
{
    public function authenticate($token)
    {
        // Load the record
        $auth = DB::table('auth')
            ->where([
                ['token', $token],
                ['active', 1]
            ])
            ->exists();
        
        return $auth;
    }
}
