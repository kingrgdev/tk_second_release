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
    public function print_schedule_list(){
        $schedule_template_list = DB::connection('mysql3')->select("SELECT * FROM schedule_template");
        $data = "";
        $data .= '
        <div id="divScheduleListTemplate" class="table-responsive col-md-12">
            <table id="tableScheduleListTemplate" name="tableScheduleListTemplate" class="table" style="width:100%; text-align:center;">
                <thead>
                    <tr>
                        <th></th>
                        <th>Template Name</th>
                        <th>Shift Time</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
        ';
        $counter = 1;
        if(count($schedule_template_list) > 0){
            foreach($schedule_template_list as $field){

                $data .= "<tr>";
                $data .= "<td><input id='rdB_CustomSchedule' type='radio' name='optradio' value='".$field->ind."'></td>";
                $data .= "<td><a id='tempDesc".$counter."'>".$field->template."</a><br>
                <small><a id='tempType".$counter."'>".$field->type."</a></small>
                </td>";
                
                if($field->type == "Regular Shift")
                {
                    $day = "";

                    $reg_in = date("g:i:a", strtotime($field->reg_in));
                    $reg_out = date("g:i:a", strtotime($field->reg_out));
        
                    $shift_time = $reg_in . " to " . $reg_out;

                    if($field->mon == "1" || $field->mon == "2"){
                        
                        $day .= "Mon,";
                    }
                    if($field->tue == "1" || $field->tue == "2"){

                        $day .= "Tue,";
                    }
                    if($field->wed == "1" || $field->wed == "2"){

                        $day .= "Wed,";
                    }
                    if($field->thu == "1" || $field->thu == "2"){

                        $day .= "Thu,";
                    }
                    if($field->fri == "1" || $field->fri == "2"){

                        $day .= "Fri,";
                    }
                    if($field->sat == "1" || $field->sat == "2"){

                        $day .= "Sat,";
                    }
                    if($field->sun == "1" || $field->sun == "2"){

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $shift_time . '</td>';
                    $data .= '<td>' . $day . '</td>';
                }
                else if($field->type == "Irregular Shift")
                {

                    $day = "";
                    $shift_time = "";
                    if($field->mon == "1" || $field->mon == "2"){

                        $mon_in = date("g:i:a", strtotime($field->mon_in));
                        $mon_out = date("g:i:a", strtotime($field->mon_out));
        
                        $shift_time .= "<p> Mon : " . $mon_in . " to " . $mon_out . "<p>";
                        
                        $day .= "Mon,";
                    }
                    if($field->tue == "1" || $field->tue == "2"){

                        $tue_in = date("g:i:a", strtotime($field->tue_in));
                        $tue_out = date("g:i:a", strtotime($field->tue_out));
        
                        $shift_time .= "Tue : " . $tue_in . " to " . $tue_out . "<p>";

                        $day .= "Tue,";
                    }
                    if($field->wed == "1" || $field->wed == "2"){

                        $wed_in = date("g:i:a", strtotime($field->wed_in));
                        $wed_out = date("g:i:a", strtotime($field->wed_out));
        
                        $shift_time .= "Wed : " . $wed_in . " to " . $wed_out . "<p>";

                        $day .= "Wed,";
                    }
                    if($field->thu == "1" || $field->thu == "2"){

                        $thu_in = date("g:i:a", strtotime($field->thu_in));
                        $thu_out = date("g:i:a", strtotime($field->thu_out));
        
                        $shift_time .= "Thu : " . $thu_in . " to " . $thu_out . "<p>";

                        $day .= "Thu,";
                    }
                    if($field->fri == "1" || $field->fri == "2"){

                        $fri_in = date("g:i:a", strtotime($field->fri_in));
                        $fri_out = date("g:i:a", strtotime($field->fri_out));
        
                        $shift_time .= "Fri : " . $fri_in . " to " . $fri_out . "<p>";

                        $day .= "Fri,";
                    }
                    if($field->sat == "1" || $field->sat == "2"){

                        $sat_in = date("g:i:a", strtotime($field->sat_in));
                        $sat_out = date("g:i:a", strtotime($field->sat_out));
        
                        $shift_time .= "Sat : " . $sat_in . " to " . $sat_out . "<p>";

                        $day .= "Sat,";
                    }
                    if($field->sun == "1" || $field->sun == "2"){

                        $sun_in = date("g:i:a", strtotime($field->sun_in));
                        $sun_out = date("g:i:a", strtotime($field->sun_out));
        
                        $shift_time .= "Sun : " . $sun_in . " to " . $sun_out . "<p>";

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $shift_time . '</td>';
                    $data .= "<td>".$day."</td>";

                }
                else if($field->type == "Flexi Shift")
                {
                    $data .= "<td></td>";
                    $data .= "<td></td>";
                }
                else if($field->type == "Free Shift")
                {
                    $data .= "<td></td>";
                    $data .= "<td></td>";
                }
                else{
                    $data .= '<td style="color:#dc3545"><i class="fa fa-times" aria-hidden="true"></i><b>&nbsp;NO SCHEDULE</b></td>';
                    $data .= '<td style="color:#dc3545"><i class="fa fa-times" aria-hidden="true"></i><b>&nbsp;NO SCHEDULE</b></td>';
                    
                }

                $data .= "</tr>";
                
            $counter++;
            }
        }
        $data .= '</tbody></table>';
        echo $data;
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
        $data .= '</tbody>
        </table>';
        echo $data;
    }
    public function save_schedule_request(Request $request){
        $message = "";
        $result = array();
        $error = array();
        $success = array();

        //Calendar
        $start_date = date("Y-m-d",strtotime($request->startDate_CWS));                          //Start Date
        $end_date = date("Y-m-d",strtotime($request->endDate_CWS));                              //End Date

        //Textboxes
        $location = $request->txtLocation;                              //Location
        $reason = $request->txtReason;                                  //Reason
        
        //Check Boxes
        $indefinite = $request->chk_ind;                                //Indefinite

        //Radio Buttons
        $chk_sched_temp = $request->chk_sched_temp;                     //Schedule Template
        $chk_sched_custom = $request->chk_sched_custom;                 //Schedule Custom

        $regular_custom = $request->regular_shift_custom;               //Regular Custom
        $irregular_custom = $request->irregular_shift_custom;           //Irregular Custom
        $flexi_custom = $request->flexi_shift_custom;                   //Flexi Custom
        $free_custom = $request->free_shift_custom;                     //Free Custom

        $optradio = $request->optradio;                                 //Shift Type Schedule Template


        $employee_schedule_request_query = "SELECT * FROM employee_schedule_request WHERE company_id = '".auth()->user()->company_id."' AND (start_date BETWEEN '".$start_date."' AND '".$end_date."')";
        $employee_schedule_request = DB::connection('mysql3')->select($employee_schedule_request_query);
        
        if(!empty($employee_schedule_request)){
            $message = "Work Schedule Request already exists!";
            $error[] = $message;
        }else{

            if($chk_sched_temp == "CHECKED")
            {
                $insert_query = new ScheduleRequestRecords;
                $insert_query->company_id = auth()->user()->company_id;
                $insert_query->template_id = $optradio;
                $insert_query->start_date = date("Y-m-d",strtotime($start_date));

                if($indefinite == "CHECKED"){
                    $insert_query->infinit = 1;
                    $insert_query->end_date = date("Y-m-d",strtotime($end_date)); //Change Field to NULL with DEFAULT value!
                }else{
                    $insert_query->infinit = 0;
                    $insert_query->end_date = date("Y-m-d",strtotime($end_date));
                }
                $insert_query->request_status = "PENDING";
                $insert_query->approved_1 = 0;
                $insert_query->approved_2 = 0;
                $insert_query->approved_1_id = "NONE";
                $insert_query->approved_2_id = "NONE";
                $insert_query->created_by = auth()->user()->name;
                $insert_query->lu_by = auth()->user()->name;
                $insert_query->timestamps = false;
                $insert_query->save();
                $message = "Work Schedule Request Successfully Applied!";
                $success[] = $message;
            }
            else if($chk_sched_custom == "CHECKED")
            {

                if($regular_custom == "CHECKED"){
                    $message = "Regular Working on it!";
                    $error[] = $message;
                }else if($irregular_custom == "CHECKED"){
                    $message = "Irregular Working on it!";
                    $error[] = $message;
                }else if($flexi_custom == "CHECKED"){
                    $message = "Flexi Working on it!";
                    $error[] = $message;
                }else if($free_custom == "CHECKED"){
                    $message = "Free Working on it!";
                    $error[] = $message;
                }
                
            }

        }






        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }
}
