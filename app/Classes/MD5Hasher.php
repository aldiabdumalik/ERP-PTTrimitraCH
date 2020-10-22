<?php

namespace App\Classes;

use Illuminate\Hashing\BcryptHasher;
use App\Models\Ekanban_Usersetup As user;

class MD5Hasher extends BcryptHasher
{
    public function check($value, $hashedValue, array $options = array())
    {
        $user = User::wherePassword(md5($value))->first();
        
        return $user ? true : false;
    }
}