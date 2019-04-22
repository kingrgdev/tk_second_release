<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $connection = 'mysql';
    protected $table = "user_type";
}
