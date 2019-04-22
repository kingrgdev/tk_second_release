<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecords extends Model
{
    protected $connection = 'mysql';
    protected $table = "attendance_records";
}
