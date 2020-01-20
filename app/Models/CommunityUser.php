<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityUser extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'users';

    public static function getUsername($email)
    {
        $username = explode('@', $email)[0] ?? guid();
        $check = self::where("username", $username)->count();
        if ($check) {
            return guid();
        }

        return $username;
    }
}
