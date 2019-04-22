<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkScheduleRecords extends Model
{
    protected $connection = 'mysql3';
    protected $table = "employee_schedule_request";
}
