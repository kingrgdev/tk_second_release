<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeRecordsController extends Controller
{
    public function index()
    {
        return view('modules.usersmodule.overtimerecords.overtimerecords');
    }
}
