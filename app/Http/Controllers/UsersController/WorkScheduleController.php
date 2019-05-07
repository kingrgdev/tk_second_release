<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use Validator;
use App\Models\ScheduleRequestRecords;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $workschedulerecords = ScheduleRequestRecords::where('company_id', auth()->user()->company_id)->get();
        return view('modules.usersmodule.workschedulerecords.workschedulerecords')->with('workschedulerecords', $workschedulerecords);
    }
    public function print_schedule(){
        $employee_schedule_request = DB::connection('mysql3')->select("SELECT * FROM employee_schedule_request WHERE company_id = '".auth()->user()->company_id."' ORDER BY created_date DESC");
        $data = "";
        $data .= '
        <div id="divScheduleRecord"class="table-responsive">
            <table id="tableScheduleRecord" name="tableScheduleRecord" class="tableScheduleRecord table table-hover" style="width:100%">
                <col>
                <colgroup span="2"></colgroup>
                <colgroup span="2"></colgroup>
                <thead>
                    <tr class="header" style="background:#f7f7f7;">
                        <th colspan="12" class="text-center">WORK SCHEDULE RECORDS</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Date Applied</th>
                        <th rowspan="2">Start Date</th>
                        <th rowspan="2">End Date</th>
                        <th rowspan="2" >Shift Type</th>
                        <th rowspan="2" >Days</th>
                        <th rowspan="2" >Applied Time of Shift</th>
                        <th colspan="2" scope="colgroup">Approval History</th>
                        <tr>
                            
                            <th scope="col">Level 1</th>
                            <th scope="col" >Level 2</th>
                            <th style="border-top:0px;">
                                Status
                            </th>
                            <th style="border-top:0px;">Actions</th>
                        </tr>
                    </tr>          
                </thead>
                <tbody>';
        $counter = 1;
        if(count($employee_schedule_request) > 0){
            foreach($employee_schedule_request as $field){

                $data .= "<tr>";
                $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($field->created_date))."</a>
                <br>
                <small>".date("l",strtotime($field->created_date))."</small>
                </td>";
                $data .= "<td><a id='startDate".$counter."'>".date("F j Y",strtotime($field->start_date))."</a></td>";
                $data .= "<td><a id='endDate".$counter."'>".date("F j Y",strtotime($field->end_date))."</a></td>";
                $data .= "<td></td>";
                $data .= "<td></td>";
                $data .= "<td></td>";
                $data .= "<td></td>";
                $data .= "<td></td>";

                if($field->request_status == "APPROVED")
                {
                    $data .= "<td style='color:#28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                }
                else if ($field->request_status == "CANCELLED"){
                    $data .= "<td style='color:#dc3545;'><i class='icon-right fa fa-times-circle'></i><b>CANCELLED</b></td>";
                }
                else if ($field->request_status == "PENDING")
                {
                    $data .="<td style='color:#E87B15;'><i class='icon-right fa fa-question-circle'></i><b>PENDING</b></td>";
                }
                else{
                    $data .="<td></td>";
                }

                if($field->request_status == "PENDING"){
                        $data .="<td><input type='button' class='btn btn-sm button red btnCancel' data-add = '".$field->id."' name='btnCancel".$counter."' id='btnCancel".$counter."' value='Cancel Alteration'></td>";
                }else{
                    $data .="<td></td>";
                }
                
                $data .= "</tr>";
                
            $counter++;
            }
        }
        $data .= '</tbody></table>';
        echo $data;
    }
}
