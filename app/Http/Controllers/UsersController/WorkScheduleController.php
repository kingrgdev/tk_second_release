<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use Validator;
use App\Models\ScheduleRequestRecords;
use App\Models\ScheduleTemplateRecords;

class WorkScheduleController extends Controller
{
    public function index(){
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
        $employee_schedule_request = DB::connection('mysql3')->select("SELECT a.id AS id, a.company_id AS company_id, a.template_id AS template_id,
        a.start_date AS start_date, a.infinit AS infinit, a.status AS status, a.end_date AS end_date, a.deleted AS deleted, a.approved_1_id AS approved_1_id,
        a.approved_2_id AS approved_2_id, a.request_status AS request_status, a.created_by AS created_by, a.created_date AS created_date,
        a.lu_by AS lu_by, a.lu_date, b.reg_in AS reg_in, b.reg_out AS reg_out,

        b.mon AS mon, b.mon_in AS mon_in, b.mon_out AS mon_out, b.tue AS tue, b.tue_in AS tue_in, b.tue_out AS tue_out,
        b.wed AS wed, b.wed_in AS wed_in, b.wed_out AS wed_out, b.thu AS thu, b.thu_in AS thu_in, b.thu_out AS thu_out, 
        b.fri AS fri, b.fri_in AS fri_in, b.fri_out AS fri_out, b.sat AS sat, b.sat_in AS sat_in, b.sat_out AS sat_out,
        b.sun AS sun, b.sun_in AS sun_in, b.sun_out AS sun_out, b.flexihrs AS flexihrs, 
        b.lunch_out AS lunch_out, b.lunch_in AS lunch_in, b.lunch_hrs AS lunch_hrs, b.schedule_desc AS schedule_desc, b.deleted AS bDeleted,
        b.ind AS ind, b.type AS type FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind
        WHERE a.deleted = 0 AND a.company_id = '".auth()->user()->company_id."' ORDER BY a.created_date DESC");

        $user_list = DB::connection('mysql')->select("SELECT * FROM users");

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
                $data .= "<td><a id='type".$counter."'>".$field->type."</a></td>";

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

                    $data .= '<td>' . $day . '</td>';
                    $data .= '<td>' . $shift_time . '</td>';
                    
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

                    $data .= '<td>' . $day . '</td>';
                    $data .= '<td>' . $shift_time . '</td>';

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

                //Approver1
                if($user_list[0]->company_id == $field->approved_1_id){
                    $data .= "<td>".$user_list[0]->name."</td>";
                }else{
                    $data .= "<td></td>";
                }
                //Approver2
                if($user_list[0]->company_id == $field->approved_2_id){
                    $data .= "<td>".$user_list[0]->name."</td>";
                }else{
                    $data .= "<td></td>";
                }
                

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

    public function cancel_schedule_request(Request $request){
        $update_query = DB::connection('mysql3')->select("UPDATE employee_schedule_request SET request_status = 'CANCELLED', lu_by = '".auth()->user()->name."', lu_date = '".NOW()."' WHERE id = '".$request->id_to_cancel."'");
        $message = "Request Cancelled Succesfully!"; 
        echo json_encode($message);
    }

    public function save_schedule_request(Request $request){
        $message = "";
        $result = array();
        $error = array();
        $success = array();

        $start_date = date("Y-m-d",strtotime($request->sched_temp_startDate));
        $end_date = date("Y-m-d",strtotime($request->sched_temp_endDate));
        $optradio = $request->optradio;
        $ind = $request->ind;

        $check_request_query = "SELECT * FROM employee_schedule_request WHERE ((start_date BETWEEN '".$start_date."' AND '".$end_date."') || (end_date BETWEEN '".$start_date."' AND '".$end_date."')) AND deleted = 0 AND company_id = '".auth()->user()->company_id."'";
        $check_request = DB::connection('mysql3')->select($check_request_query);

        if(!empty($check_request)){
            $message = "Invalid Work Schedule Request Found";
            $error[] = $message;
        }else{
            if($ind == "TRUE")
            {
                $insert_query = new ScheduleRequestRecords;
                $insert_query->company_id = auth()->user()->company_id;
                $insert_query->template_id = $optradio;
                $insert_query->start_date = date("Y-m-d",strtotime($start_date));

                $insert_query->infinit = 1;
                $insert_query->end_date = date("Y-m-d",strtotime($end_date)); //Change Field to NULL with DEFAULT value!

                $insert_query->request_status = "PENDING";
                $insert_query->approved_1 = 0;
                $insert_query->approved_2 = 0;
                $insert_query->approved_1_id = "NONE";
                $insert_query->approved_2_id = "NONE";
                $insert_query->created_by = auth()->user()->name;
                $insert_query->lu_by = auth()->user()->name;
                $insert_query->timestamps = false;
                $insert_query->save();

                $message = "Schedule Successfully Requested!";
                $success[] = $message;
            }
            else if($ind == "FALSE")
            {
                $insert_query = new ScheduleRequestRecords;
                $insert_query->company_id = auth()->user()->company_id;
                $insert_query->template_id = $optradio;
                $insert_query->start_date = date("Y-m-d",strtotime($start_date));

                $insert_query->infinit = 0;
                $insert_query->end_date = date("Y-m-d",strtotime($end_date)); //Change Field to NULL with DEFAULT value!

                $insert_query->request_status = "PENDING";
                $insert_query->approved_1 = 0;
                $insert_query->approved_2 = 0;
                $insert_query->approved_1_id = "NONE";
                $insert_query->approved_2_id = "NONE";
                $insert_query->created_by = auth()->user()->name;
                $insert_query->lu_by = auth()->user()->name;
                $insert_query->timestamps = false;
                $insert_query->save();

                $message = "Schedule Successfully Requested!";
                $success[] = $message;
            }

        }

        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function save_custom_regular(Request $request){
        $message = "";
        $result = array();
        $error = array();
        $success = array();

        $shift_in = date("H:i:s",strtotime($request->regShiftIn));
        $shift_out = date("H:i:s",strtotime($request->regShiftOut));

        $schedule_description = $request->scheduleDescription;
        $template_name = $request->templateName;
        
        $data_days = $request->dataDays;
        $data_rest = $request->dataRest;

        $lunch_out = date("H:i:s",strtotime($request->addLunchOut));
        $lunch_in = date("H:i:s",strtotime($request->addLunchIn));
        $lunch_hours = $request->hiddenLunchHours;


        if($template_name == ""){
            $message = "Template Name Field Required!";
            $error[] = $message;
        }

        else if($schedule_description == ""){
            $message = "Schedule Description Field Required!";
            $error[] = $message;
        }

        else if($lunch_out == ""){
            $message = "Lunch Out Field Required!";
            $error[] = $message;
        }

        else if($lunch_in == ""){
            $message = "Lunch In Field Required!";
            $error[] = $message;
        }

        else if($lunch_out >= $lunch_in){
            $message = "Lunch Out must be greater than Lunch In!";
            $error[] = $message;
        }

        else if($shift_in == ""){
            $message = "Shift In Field Required!";
            $error[] = $message;
        }

        else if($shift_out == ""){
            $message = "Shift Out Field Required!";
            $error[] = $message;
        }

        else
        {
            
            $insert_query = new ScheduleTemplateRecords;
            $insert_query->template = $template_name;
            $insert_query->type = "Regular Shift";
            $insert_query->reg_in = $shift_in;
            $insert_query->reg_out = $shift_out;

            //Monday
            if (in_array('1', $data_days, true)){
                if (in_array('1', $data_rest, true))
                {
                    $insert_query->mon = "2";
                }
                else
                {
                    $insert_query->mon = "1";
                }
            }
            else
            {
                $insert_query->mon = "0";
            }

            //Tuesday
            if (in_array('2', $data_days, true)){
                if (in_array('2', $data_rest, true))
                {
                    $insert_query->tue = "2";
                }
                else
                {
                    $insert_query->tue = "1";
                }
            }
            else
            {
                $insert_query->tue = "0";
            }

            //Wednesday
            if (in_array('3', $data_days, true)){
                if (in_array('3', $data_rest, true))
                {
                    $insert_query->wed = "2";
                }
                else
                {
                    $insert_query->wed = "1";
                }
            }
            else
            {
                $insert_query->wed = "0";
            }

            //Thursday
            if (in_array('4', $data_days, true))
            {
                if (in_array('4', $data_rest, true))
                {
                    $insert_query->thu = "2";
                }
                else
                {
                    $insert_query->thu = "1";
                }
            }
            else
            {
                $insert_query->thu = "0";
            }

            //Friday
            if (in_array('5', $data_days, true)){
                if (in_array('5', $data_rest, true))
                {
                    $insert_query->fri = "2";
                }
                else
                {
                    $insert_query->fri = "1";
                }
            }
            else
            {
                $insert_query->fri = "0";
            }

            //Saturday
            if (in_array('6', $data_days, true)){
                if (in_array('6', $data_rest, true))
                {
                    $insert_query->sat = "2";
                }
                else
                {
                    $insert_query->sat = "1";
                }
            }
            else
            {
                $insert_query->sat = "0";
            }

            //Sunday
            if (in_array('7', $data_days, true))
            {
                if (in_array('7', $data_rest, true))
                {
                    $insert_query->sun = "2";
                }
                else
                {
                    $insert_query->sun = "1";
                }
            }
            else
            {
                $insert_query->sun = "0";
            }
            
            if($request->input('hiddenLunchHours') != ""){
                $insert_query->lunch_out = $lunch_out;
                $insert_query->lunch_in = $lunch_in;
                $insert_query->lunch_hrs = $lunch_hours;
            }
            
            $insert_query->schedule_desc = $schedule_description;
            $insert_query->created_by = auth()->user()->name;
            $insert_query->lu_by = auth()->user()->name;
            $insert_query->timestamps = false;
            $insert_query->save();

            $message = "Custom Schedule Successfully Added!";
            $success[] = $message;

        }


        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function save_custom_irregular(Request $request){

        $message = "";
        $result = array();
        $error = array();
        $success = array();

        //Post Variables
            $tempName = $request->templateName;
            $schedDesc = $request->scheduleDescription;

            $data_days = $request->dataDays;
            $data_rest = $request->dataRest;

            $lunch_out = date("H:i:s",strtotime($request->addLunchOut));
            $lunch_in = date("H:i:s",strtotime($request->addLunchIn));

            $lunch_hours = $request->hiddenLunchHours;
        //Post Variables

        if($tempName == ""){
            $message = "Template Name Field Required!";
            $error[] = $message;
        }else if($schedDesc == ""){
            $message = "Template Name Field Required!";
            $error[] = $message;
        }else if($lunch_out == ""){
            $message = "Lunch Out Field Required!";
            $error[] = $message;
        }else if($lunch_in == ""){
            $message = "Lunch In Field Required!";
            $error[] = $message;
        }else if($lunch_out >= $lunch_in){
            $message = "Lunch In must be greater than Lunch Out!";
            $error[] = $message;
        }else{
        
            $insert_query = new ScheduleTemplateRecords;
            $insert_query->template = $tempName;
            $insert_query->type = "Irregular Shift";
            $insert_query->reg_in = NULL;
            $insert_query->reg_out = NULL;


            //Monday
            if(in_array('1', $data_days, true)){

                $mon_in = date("H:i:s", strtotime($request->irr_in_mon));
                $mon_out = date("H:i:s", strtotime($request->irr_out_mon));

                $insert_query->mon_in = $mon_in;
                $insert_query->mon_out = $mon_out;

                if(in_array('1', $data_rest, true))
                {
                   $insert_query->mon = "2";
                }
                else
                {
                   $insert_query->mon = "1";
                }
            }
            else
            {
                $insert_query->mon = "0";
            }

            //Tuesday
            if(in_array('2', $data_days, true))
            {
                $tue_in = date("H:i:s", strtotime($request->irr_in_tue));
                $tue_out = date("H:i:s", strtotime($request->irr_out_tue));

                $insert_query->tue_in = $tue_in;
                $insert_query->tue_out = $tue_out;

                if(in_array('2', $data_rest, true))
                {
                   $insert_query->tue = "2";
                }
                else
                {
                   $insert_query->tue = "1";
                }
            }
            else
            {
                $insert_query->tue = "0";
            }

            //Wednesday
            if(in_array('3', $data_days, true))
            {
                $wed_in = date("H:i:s", strtotime($request->irr_in_wed));
                $wed_out = date("H:i:s", strtotime($request->irr_out_wed));

                $insert_query->wed_in = $wed_in;
                $insert_query->wed_out = $wed_out;

                if(in_array('3', $data_rest, true))
                {
                   $insert_query->wed = "2";
                }
                else
                {
                   $insert_query->wed = "1";
                }
            }
            else
            {
                $insert_query->wed = "0";
            }

            //Thursday
            if(in_array('4', $data_days, true))
            {
                $thu_in = date("H:i:s", strtotime($request->irr_in_thu));
                $thu_out = date("H:i:s", strtotime($request->irr_out_thu));

                $insert_query->thu_in = $thu_in;
                $insert_query->thu_out = $thu_out;

                if(in_array('4', $data_rest, true))
                {
                   $insert_query->thu = "2";
                }
                else
                {
                   $insert_query->thu = "1";
                }
            }
            else
            {
                $insert_query->thu = "0";
            }

            //Friday
            if(in_array('5', $data_days, true))
            {
                $fri_in = date("H:i:s", strtotime($request->irr_in_fri));
                $fri_out = date("H:i:s", strtotime($request->irr_out_fri));

                $insert_query->fri_in = $fri_in;
                $insert_query->fri_out = $fri_out;

                if(in_array('5', $data_rest, true))
                {
                   $insert_query->fri = "2";
                }
                else
                {
                   $insert_query->fri = "1";
                }
            }
            else
            {
                $insert_query->fri = "0";
            }

            //Saturday
            if(in_array('6', $data_days, true))
            {
                $sat_in = date("H:i:s", strtotime($request->irr_in_sat));
                $sat_out = date("H:i:s", strtotime($request->irr_out_sat));

                $insert_query->sat_in = $sat_in;
                $insert_query->sat_out = $sat_out;

                if(in_array('6', $data_rest, true))
                {
                   $insert_query->sat = "2";
                }
                else
                {
                   $insert_query->sat = "1";
                }
            }
            else
            {
                $insert_query->sat = "0";
            }

            //Sunday
            if(in_array('7', $data_days, true))
            {
                $sun_in = date("H:i:s", strtotime($request->irr_in_sun));
                $sun_out = date("H:i:s", strtotime($request->irr_out_sun));

                $insert_query->sun_in = $sun_in;
                $insert_query->sun_out = $sun_out;

                if(in_array('7', $data_rest, true))
                {
                   $insert_query->sun = "2";
                }
                else
                {
                   $insert_query->sun = "1";
                }
            }
            else
            {
                $insert_query->sun = "0";
            }

            if($lunch_hours != ""){
                $insert_query->lunch_out = $lunch_out;
                $insert_query->lunch_in = $lunch_in;
                $insert_query->lunch_hrs = $lunch_hours;
            }

            $insert_query->schedule_desc = $schedDesc;
            $insert_query->created_by = auth()->user()->name;
            $insert_query->lu_by = auth()->user()->name;
            $insert_query->timestamps = false;
            $insert_query->save();

            $message = "Custom Schedule Successfully Added!";
            $success[] = $message;

        }
        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function save_custom_flexi(Request $request){
    }

    public function save_custom_free(Request $request){
    }

    

}
