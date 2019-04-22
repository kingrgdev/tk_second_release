<?php

namespace App\Http\Controllers\ErrorController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public function index()
    {
        return view ('modules.errorpage');
    }
}
