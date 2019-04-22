<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRecords extends Model
{
    protected $connection = 'mysql';
    protected $table = "overtime_records";
}
