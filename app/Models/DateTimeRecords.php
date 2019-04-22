<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateTimeRecords extends Model
{
    protected $connection = 'mysql2';
    protected $table = "date_and_time_records";
}
