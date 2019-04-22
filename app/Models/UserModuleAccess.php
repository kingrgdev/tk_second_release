<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModuleAccess extends Model
{
    protected $connection = 'mysql';
    protected $table = "user_module_access";
}
