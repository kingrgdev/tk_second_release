<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use DateTime;
use App\Models\OvertimeRecords;


class OvertimeRecordsController extends Controller
{
    public function index(){

        return view('modules.usersmodule.overtimerecords.overtimerecords');
    }
    public function print_overtime_now(){

        $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."' ORDER BY created_at DESC");
        $user_list = DB::connection('mysql')->select("SELECT * FROM users");
        $data = "";
        $data .= '<div id="divOvertimeRecord"class="table-responsive">
                    <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-hover" style="width:100%">
                        <col>
                        <colgroup span="2"></colgroup>
                        <colgroup span="2"></colgroup>
                        <thead>
                            <tr class="header" style="background:#f7f7f7;">
                                <th colspan="10" class="text-center">OVERTIME RECORDS</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Date Applied</th>
                                <th rowspan="2">Applied Time In</th>
                                <th rowspan="2">Applied Time Out</th>
                                <th rowspan="2">Shift Type</th>
                                <th rowspan="2" >Reason</th>
                                <th rowspan="2" >Total Hours</th>
                                <th colspan="2" scope="colgroup" style="">Approval History</th>
                                <tr>
                                    
                                    <th scope="col">Level 1</th>
                                    <th scope="col" >Level 2</th>
                                    <th style="border-top:0px;">
                                        Status
                                    </th>
                                    <th style="border-top:0px;">
                                        Actions
                                    </th>
                                </tr>
                            </tr>          
                        </thead>';
        $counter = 1;
        if(count($view_overtime_records) > 0){
            foreach($view_overtime_records as $field){

                $data .= "<tr>";
                    $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($field->date_applied))."</a>
                    <br>
                    <small><a id='dayApplied".$counter."'>".date("l",strtotime($field->date_applied))."</a></small>
                    </td>";

                    $data .= "<td><a id='date_timein".$counter."'>".date("F j Y",strtotime($field->date_timein))."</a>
                    <br>
                    <a id='date_timein_clck".$counter."'>".date("h:i A",strtotime($field->date_timein))."</a>
                    </td>";
                    $data .= "<td>
                    <a id='date_timeout".$counter."'>".date("F j Y",strtotime($field->date_timeout))."</a>
                    <br>
                    <a id='date_timeout_clck".$counter."'>".date("h:i A",strtotime($field->date_timeout))."</a>
                    </td>";
                    $data .= "<td><a id='shift_applied".$counter."'>".$field->shift_applied."</a></td>";
                    $data .= "<td><a id='reason".$counter."'>".$field->reason."</a></td>";
                    $data .= "<td><a id='total_hrs".$counter."'>".$field->total_hrs."</a></td>";

                    if($field->approved_1_id == $user_list[0]->company_id){
                        $data .= "<td><a id='approved_1_id".$counter."'>".$user_list[0]->name."</a></td>";
                    }else{
                        $data .= "<td></td>";
                    }

                    if($field->approved_2_id == $user_list[0]->company_id){
                        $data .= "<td><a id='approved_2_id".$counter."'>".$user_list[0]->name."</a></td>";
                    }else{
                        $data .= "<td></td>";
                    }

                    if($field->status == "APPROVED")
                    {
                        $data .= "<td style='color:#28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                    }
                    else if ($field->status == "CANCELLED"){
                        $data .= "<td style='color:#dc3545;'><i class='icon-right fa fa-times-circle'></i><b>CANCELLED</b></td>";
                    }
                    else if ($field->status == "PENDING")
                    {
                        $data .="<td style='color:#E87B15;'><i class='icon-right fa fa-question-circle'></i><b>PENDING</b></td>";
                    }
                    else{
                        $data .="<td></td>";
                    }

                    if($field->status == "PENDING"){
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
    public function cancel_overtime(Request $request){
        $cancel_overtime = OvertimeRecords::find($request->id_to_cancel);
        $cancel_overtime->status = 'CANCELLED';
        $cancel_overtime->lu_by = "Legendary MG Wazzap Man";
        $cancel_overtime->save();
        $message = "Overtime Cancelled Succesfully!"; 
        echo json_encode($message);
    }
    public function save_overtime(Request $request){

        $message = "";

        $result = array();
        $error = array();
        $success = array();
        
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

        $overtime = "false";
        $total_hours = 0.0;
        $hour = 0.0;
        $total_flexihrs = 0.0;

        $sched_date = date("Y-m-d",strtotime($request->schedDate));

        $timein = date("H:i:s",strtotime($request->timeIn));
        $datetimein = date("Y-m-d H:i:s", strtotime("$sched_date $timein"));

        $datetimeout = date("Y-m-d H:i:s",strtotime($request->timeOut));
        
        $shift_type = $request->cmbShift;
        $reason = $request->txtReason;

        //Checks if overtime already exists!
        $check_ot_query = "SELECT company_id, sched_date, status FROM overtime_records WHERE sched_date = '" . $sched_date . "' AND company_id = '".auth()->user()->company_id."' AND status <> 'CANCELLED'";
        $check_ot = DB::connection('mysql')->select($check_ot_query);

        //Checks if employee exists in date_and_time_records from payroll
        $check_dtr_query = "SELECT * FROM date_and_time_records WHERE sched_date = '".$sched_date."' AND company_id = '".auth()->user()->company_id."'";
        $check_dtr = DB::connection('mysql2')->select($check_dtr_query);

        //Checks if employee exists in punch_alteration_records
        $check_alter_query = "SELECT * FROM alteration_records WHERE sched_date = '".$sched_date."' AND company_id = '".auth()->user()->company_id."'";
        $check_alter = DB::connection('mysql')->select($check_alter_query);

        //Condition if Employee DTR Exists
        if(!empty($check_dtr))
        {
            if($check_dtr[0]->sched_date == $sched_date){

                //This will check the DTR total hours of an employee
                $dtr_day1 = $check_dtr[0]->date_time_in;
                $dtr_day1 = strtotime($dtr_day1);
                $dtr_day2 = $check_dtr[0]->date_time_out;
                $dtr_day2 = strtotime($dtr_day2);
                $diffHours = round(($dtr_day2 - $dtr_day1) / 3600);


                //Condition if 10 Hours work didn't exceed!
                if($diffHours < 11){
                    $message = "Your DTR for this day didn't exceed 10 hours work!";
                    $error[] = $message;
                }else{
                    

                    //If Overtime is already exists
                    if(!empty($check_ot)){
                        
                        $message = "Your overtime already exists!";
                        $error[] = $message;
                        
                    }else{
                        //Employee Schedule Request
                        $select_schedule_request_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                        b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                        b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                        WHERE a.deleted = '0' AND '" . $sched_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '".auth()->user()->company_id."'";
                        $select_schedule_request = DB::connection('mysql3')->select($select_schedule_request_query);

                        if(!empty($select_schedule_request)){

                            if($select_schedule_request[0]->type == "Regular Shift"){
                                $day = date("N", strtotime($request->schedDate)); //converts the date into day
                                            
                                //Monday
                                    if($day == "1"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "2"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Monday

                                //Tuesday
                                    if($day == "2"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "3"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Tuesday

                                //Wednesday
                                    if($day == "3"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else
                                                {
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "4"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Wednesday

                                //Thursday
                                    if($day == "4"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "5"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Thursday
                                
                                //Friday
                                    if($day == "5"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "6"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Friday

                                //Saturday
                                    if($day == "6"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "7"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Saturday

                                //Sunday
                                    if($day == "7"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $select_schedule_request[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $select_schedule_request[0]->reg_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                        //employee datetime out
                                        $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "1"){
                                                        if($reg_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($reg_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                //End of Sunday
                            }
                            else if($select_schedule_request[0]->type == "Irregular Shift"){

                                $day = date("N", strtotime($request->schedDate)); //converts the date into day

                                //Monday
                                    if($day == "1"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $mon_in = $select_schedule_request[0]->mon_in;
                                        //time out of employee 
                                        $mon_out = $select_schedule_request[0]->mon_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $mon_datetimein = new DateTime($date_in . " " . $mon_in);
                                        //employee datetime out
                                        $mon_datetimeout = new DateTime($date_in . " " . $mon_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $tue_in = $select_schedule_request[0]->tue_in;
                                            $tue_datetimein = new DateTime($date_out . " " . $tue_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($mon_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
    
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "2"){
                                                        if($tue_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($tue_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                            $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of Monday

                                //Tuesday
                                    if($day == "2"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $tue_in = $select_schedule_request[0]->tue_in;
                                        //time out of employee 
                                        $tue_out = $select_schedule_request[0]->tue_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $tue_datetimein = new DateTime($date_in . " " . $tue_in);
                                        //employee datetime out
                                        $tue_datetimeout = new DateTime($date_in . " " . $tue_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $wed_in = $select_schedule_request[0]->wed_in;
                                            $wed_datetimein = new DateTime($date_out . " " . $wed_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($tue_datetimeout <= $_datetimein){
    
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
    
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "3"){
                                                        if($wed_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($wed_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                            $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of Tuesday

                                //Wednesday
                                    if($day == "3"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $wed_in = $select_schedule_request[0]->wed_in;
                                        //time out of employee 
                                        $wed_out = $select_schedule_request[0]->wed_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $wed_datetimein = new DateTime($date_in . " " . $wed_in);
                                        //employee datetime out
                                        $wed_datetimeout = new DateTime($date_in . " " . $wed_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $thu_in = $select_schedule_request[0]->thu_in;
                                            $thu_datetimein = new DateTime($date_out . " " . $thu_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($wed_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "4"){
                                                        if($thu_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($thu_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                            $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of Wednesday

                                //Thursday
                                    if($day == "4"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $thu_in = $select_schedule_request[0]->thu_in;
                                        //time out of employee 
                                        $thu_out = $select_schedule_request[0]->thu_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $thu_datetimein = new DateTime($date_in . " " . $thu_in);
                                        //employee datetime out
                                        $thu_datetimeout = new DateTime($date_in . " " . $thu_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $fri_in = $select_schedule_request[0]->fri_in;
                                            $fri_datetimein = new DateTime($date_out . " " . $fri_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($thu_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
    
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "5"){
                                                        if($fri_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($fri_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                            $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of Thursday

                                //Friday
                                    if($day == "5"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $fri_in = $select_schedule_request[0]->fri_in;
                                        //time out of employee 
                                        $fri_out = $select_schedule_request[0]->fri_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $fri_datetimein = new DateTime($date_in . " " . $fri_in);
                                        //employee datetime out
                                        $fri_datetimeout = new DateTime($date_in . " " . $fri_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $sat_in = $select_schedule_request[0]->sat_in;
                                            $sat_datetimein = new DateTime($date_out . " " . $sat_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($fri_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
    
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "6"){
                                                        if($sat_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($sat_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                            $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of Friday

                                //Saturday
                                    if($day == "6"){
    
                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                        //time in of employee 
                                        $sat_in = $select_schedule_request[0]->sat_in;
                                        //time out of employee 
                                        $sat_out = $select_schedule_request[0]->sat_out;
    
                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);
    
                                        //employee datetime in
                                        $sat_datetimein = new DateTime($date_in . " " . $sat_in);
                                        //employee datetime out
                                        $sat_datetimeout = new DateTime($date_in . " " . $sat_out);
    
                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){
    
                                            $chkDay = date("N", strtotime($date_out));
    
                                            //get emp time in
                                            $sun_in = $select_schedule_request[0]->sun_in;
                                            $sun_datetimein = new DateTime($date_out . " " . $sun_in);
    
                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                            if($sat_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){
    
                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "7"){
                                                        if($sun_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($sun_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{
    
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                            $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){
    
                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{
    
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
    
                                    }
                                //End of saturday

                                //Sunday
                                    if($day == "7"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $sun_in = $select_schedule_request[0]->sun_in;
                                        //time out of employee 
                                        $sun_out = $select_schedule_request[0]->sun_out;

                                        //datetime in
                                        $_datetimein = new DateTime($datetimein);
                                        //datetime out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //employee datetime in
                                        $sun_datetimein = new DateTime($date_in . " " . $sun_in);
                                        //employee datetime out
                                        $sun_datetimeout = new DateTime($date_in . " " . $sun_out);

                                        //check if the application for overtime is between the next day
                                        if($date_in != $date_out){

                                            $chkDay = date("N", strtotime($date_out));

                                            //get emp time in
                                            $mon_in = $select_schedule_request[0]->mon_in;
                                            $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($sun_datetimeout <= $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = "Your overtime exceeds 1 day!";
                                                    $error[] = $message;
                                                }
                                                else{
                                                    //kapag next day ang inapply na overtime
                                                    if($chkDay == "1"){
                                                        if($mon_in == "00:00:00"){
        
                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                        else if($mon_datetimein < $_datetimeout){
        
                                                            $message = "Overtime cannot be applied!";
                                                            $error[] = $message;
                                                        }
                                                        else{

                                                            $overtime = "true";
                                                            $interval = $_datetimeout->diff($_datetimein);
                                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                        }
                                                    }
                                                }
                                            }
                                            else{

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                            $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                //End of Sunday
                            }
                            else if($select_schedule_request[0]->type == "Flexi Shift"){
 
                                $overtime = "false";

                                $date_in = date("Y-m-d", strtotime($datetimein));
                                
                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                $_datetimein = new DateTime($datetimein);
                                
                                $_datetimeout = new DateTime($datetimeout);

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($date_out > $valid_date_out)
                                    {
                                        $message = "Your overtime exceeds 1 day!";
                                        $error[] = $message;
                                    }
                                    else{
                                        
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                else{
                                    $overtime = "true";
                                    $interval = $_datetimeout->diff($_datetimein);
                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                }
                            }
                            else if($select_schedule_request[0]->type == "Free Shift"){
                                $overtime = "false";

                                $date_in = date("Y-m-d", strtotime($datetimein));
                                
                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                $_datetimein = new DateTime($datetimein);
                                
                                $_datetimeout = new DateTime($datetimeout);

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($date_out > $valid_date_out)
                                    {
                                        $message = "Your overtime exceeds 1 day!";
                                        $error[] = $message;
                                    }
                                    else{
                                        
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                else{
                                    $overtime = "true";
                                    $interval = $_datetimeout->diff($_datetimein);
                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                }
                            }
                            if($overtime == "true"){
                                $insert_query = new OvertimeRecords;
                                $insert_query->company_id = auth()->user()->company_id;
                                $insert_query->date_applied = $date_now;
                                $insert_query->sched_date = $sched_date;
                                $insert_query->shift_applied = $shift_type;
                                $insert_query->date_timein = $datetimein;
                                $insert_query->date_timeout = $datetimeout;
                                $insert_query->total_hrs = $hour;
                                $insert_query->reason = $reason;
                                $insert_query->status = "PENDING";
                                $insert_query->lu_by = auth()->user()->name;
                                $insert_query->save();
                                $message = "Overtime Successfully Applied!";
                                $success[] = $message;
                            }
                            
                        }    
                        
                        else{

                            $select_schedule_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                            WHERE a.deleted = '0' AND a.company_id = '".auth()->user()->company_id."'";

                            $select_schedule = DB::connection('mysql3')->select($select_schedule_query);
                            
                            if(!empty($select_schedule)){
                                
                                if($select_schedule[0]->type == "Regular Shift"){
                                    $day = date("N", strtotime($request->schedDate)); //converts the date into day
                                                
                                    //Monday
                                        if($day == "1"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "2"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Monday

                                    //Tuesday
                                        if($day == "2"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "3"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Tuesday

                                    //Wednesday
                                        if($day == "3"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else
                                                    {
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "4"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Wednesday

                                    //Thursday
                                        if($day == "4"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "5"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Thursday
                                    
                                    //Friday
                                        if($day == "5"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "6"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Friday

                                    //Saturday
                                        if($day == "6"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "7"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Saturday

                                    //Sunday
                                        if($day == "7"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "1"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Sunday
                                }
                                else if($select_schedule[0]->type == "Irregular Shift"){

                                    $day = date("N", strtotime($request->schedDate)); //converts the date into day

                                    //Monday
                                        if($day == "1"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $mon_in = $select_schedule[0]->mon_in;
                                            //time out of employee 
                                            $mon_out = $select_schedule[0]->mon_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $mon_datetimein = new DateTime($date_in . " " . $mon_in);
                                            //employee datetime out
                                            $mon_datetimeout = new DateTime($date_in . " " . $mon_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $tue_in = $select_schedule[0]->tue_in;
                                                $tue_datetimein = new DateTime($date_out . " " . $tue_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($mon_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "2"){
                                                            if($tue_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($tue_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                                $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Monday
    
                                    //Tuesday
                                        if($day == "2"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $tue_in = $select_schedule[0]->tue_in;
                                            //time out of employee 
                                            $tue_out = $select_schedule[0]->tue_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $tue_datetimein = new DateTime($date_in . " " . $tue_in);
                                            //employee datetime out
                                            $tue_datetimeout = new DateTime($date_in . " " . $tue_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $wed_in = $select_schedule[0]->wed_in;
                                                $wed_datetimein = new DateTime($date_out . " " . $wed_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($tue_datetimeout <= $_datetimein){
        
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "3"){
                                                            if($wed_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($wed_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                                $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Tuesday
    
                                    //Wednesday
                                        if($day == "3"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $wed_in = $select_schedule[0]->wed_in;
                                            //time out of employee 
                                            $wed_out = $select_schedule[0]->wed_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $wed_datetimein = new DateTime($date_in . " " . $wed_in);
                                            //employee datetime out
                                            $wed_datetimeout = new DateTime($date_in . " " . $wed_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $thu_in = $select_schedule[0]->thu_in;
                                                $thu_datetimein = new DateTime($date_out . " " . $thu_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($wed_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "4"){
                                                            if($thu_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($thu_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                                $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Wednesday
    
                                    //Thursday
                                        if($day == "4"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $thu_in = $select_schedule[0]->thu_in;
                                            //time out of employee 
                                            $thu_out = $select_schedule[0]->thu_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $thu_datetimein = new DateTime($date_in . " " . $thu_in);
                                            //employee datetime out
                                            $thu_datetimeout = new DateTime($date_in . " " . $thu_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $fri_in = $select_schedule[0]->fri_in;
                                                $fri_datetimein = new DateTime($date_out . " " . $fri_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($thu_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "5"){
                                                            if($fri_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($fri_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                                $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Thursday
    
                                    //Friday
                                        if($day == "5"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $fri_in = $select_schedule[0]->fri_in;
                                            //time out of employee 
                                            $fri_out = $select_schedule[0]->fri_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $fri_datetimein = new DateTime($date_in . " " . $fri_in);
                                            //employee datetime out
                                            $fri_datetimeout = new DateTime($date_in . " " . $fri_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $sat_in = $select_schedule[0]->sat_in;
                                                $sat_datetimein = new DateTime($date_out . " " . $sat_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($fri_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "6"){
                                                            if($sat_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($sat_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                                $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Friday
    
                                    //Saturday
                                        if($day == "6"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $sat_in = $select_schedule[0]->sat_in;
                                            //time out of employee 
                                            $sat_out = $select_schedule[0]->sat_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $sat_datetimein = new DateTime($date_in . " " . $sat_in);
                                            //employee datetime out
                                            $sat_datetimeout = new DateTime($date_in . " " . $sat_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $sun_in = $select_schedule[0]->sun_in;
                                                $sun_datetimein = new DateTime($date_out . " " . $sun_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($sat_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "7"){
                                                            if($sun_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($sun_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                                $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of saturday

                                    //Sunday
                                        if($day == "7"){

                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $sun_in = $select_schedule[0]->sun_in;
                                            //time out of employee 
                                            $sun_out = $select_schedule[0]->sun_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $sun_datetimein = new DateTime($date_in . " " . $sun_in);
                                            //employee datetime out
                                            $sun_datetimeout = new DateTime($date_in . " " . $sun_out);

                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $mon_in = $select_schedule[0]->mon_in;
                                                $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($sun_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "1"){
                                                            if($mon_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($mon_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{

                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                                $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }

                                        }
                                    //End of Sunday
                                }
                                else if($select_schedule[0]->type == "Flexi Shift"){
     
                                    $overtime = "false";

                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    
                                    $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                    $_datetimein = new DateTime($datetimein);
                                    
                                    $_datetimeout = new DateTime($datetimeout);
    
                                    if($date_in != $date_out){
    
                                        $chkDay = date("N", strtotime($date_out));
    
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                        if($date_out > $valid_date_out)
                                        {
                                            $message = "Your overtime exceeds 1 day!";
                                            $error[] = $message;
                                        }
                                        else{
                                            
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    else{
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                else if($select_schedule[0]->type == "Free Shift"){
                                    $overtime = "false";

                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    
                                    $date_out = date("Y-m-d", strtotime($datetimeout));
    
                                    $_datetimein = new DateTime($datetimein);
                                    
                                    $_datetimeout = new DateTime($datetimeout);
    
                                    if($date_in != $date_out){
    
                                        $chkDay = date("N", strtotime($date_out));
    
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
    
                                        if($date_out > $valid_date_out)
                                        {
                                            $message = "Your overtime exceeds 1 day!";
                                            $error[] = $message;
                                        }
                                        else{
                                            
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    else{
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                if($overtime == "true"){
                                    $insert_query = new OvertimeRecords;
                                    $insert_query->company_id = auth()->user()->company_id;
                                    $insert_query->date_applied = $date_now;
                                    $insert_query->sched_date = $sched_date;
                                    $insert_query->shift_applied = $shift_type;
                                    $insert_query->date_timein = $datetimein;
                                    $insert_query->date_timeout = $datetimeout;
                                    $insert_query->total_hrs = $hour;
                                    $insert_query->reason = $reason;
                                    $insert_query->status = "PENDING";
                                    $insert_query->lu_by = auth()->user()->name;
                                    $insert_query->save();
                                    $message = "Overtime Successfully Applied!";
                                    $success[] = $message;
                                }
                            }
                            
                        }
                    }
                }
                
            }else{
                $message = "No Matched DTR for this employee";
                $error[] = $message;
            }
        }else{
            
            //This will check if there's alteration exists!
            if(!empty($check_alter)){
                
                if($check_alter[0]->sched_date == $sched_date)
                {
                    //This will check the DTR total hours of an employee
                    $dtr_day1 = $check_alter[0]->new_time_in;
                    $dtr_day1 = strtotime($dtr_day1);
                    $dtr_day2 = $check_alter[0]->new_time_out;
                    $dtr_day2 = strtotime($dtr_day2);
                    $diffHours = round(($dtr_day2 - $dtr_day1) / 3600);

                    //Condition if 10 Hours work didn't exceed!
                    if($diffHours < 11){
                        $message = "Your Alteration for this day didn't exceed 10 hours work!";
                        $error[] = $message;
                    }else{
                        //If Overtime is already exists
                        if(!empty($check_ot)){
                            $message = "Your overtime already exists!";
                            $error[] = $message;
                        }
                        else
                        {
                            //Employee Schedule Request
                            $select_schedule_request_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                            b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                            WHERE a.deleted = '0' AND '" . $sched_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '".auth()->user()->company_id."'";
                            $select_schedule_request = DB::connection('mysql3')->select($select_schedule_request_query);

                            if(!empty($select_schedule_request)){

                                if($select_schedule_request[0]->type == "Regular Shift"){
                                    $day = date("N", strtotime($request->schedDate)); //converts the date into day
                                                
                                    //Monday
                                        if($day == "1"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "2"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Monday

                                    //Tuesday
                                        if($day == "2"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "3"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Tuesday

                                    //Wednesday
                                        if($day == "3"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else
                                                    {
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "4"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Wednesday

                                    //Thursday
                                        if($day == "4"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "5"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Thursday
                                    
                                    //Friday
                                        if($day == "5"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "6"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Friday

                                    //Saturday
                                        if($day == "6"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "7"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Saturday

                                    //Sunday
                                        if($day == "7"){

                                            $overtime = "false";

                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $reg_in = $select_schedule_request[0]->reg_in;
                                            //time out of employee 
                                            $reg_out = $select_schedule_request[0]->reg_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                            //employee datetime out
                                            $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $reg_in = $select_schedule_request[0]->reg_in;
                                                $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($reg_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "1"){
                                                            if($reg_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($reg_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
                                        }
                                    //End of Sunday
                                }
                                else if($select_schedule_request[0]->type == "Irregular Shift"){

                                    $day = date("N", strtotime($request->schedDate)); //converts the date into day

                                    //Monday
                                        if($day == "1"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $mon_in = $select_schedule_request[0]->mon_in;
                                            //time out of employee 
                                            $mon_out = $select_schedule_request[0]->mon_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $mon_datetimein = new DateTime($date_in . " " . $mon_in);
                                            //employee datetime out
                                            $mon_datetimeout = new DateTime($date_in . " " . $mon_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $tue_in = $select_schedule_request[0]->tue_in;
                                                $tue_datetimein = new DateTime($date_out . " " . $tue_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($mon_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "2"){
                                                            if($tue_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($tue_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                                $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Monday

                                    //Tuesday
                                        if($day == "2"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $tue_in = $select_schedule_request[0]->tue_in;
                                            //time out of employee 
                                            $tue_out = $select_schedule_request[0]->tue_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $tue_datetimein = new DateTime($date_in . " " . $tue_in);
                                            //employee datetime out
                                            $tue_datetimeout = new DateTime($date_in . " " . $tue_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $wed_in = $select_schedule_request[0]->wed_in;
                                                $wed_datetimein = new DateTime($date_out . " " . $wed_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($tue_datetimeout <= $_datetimein){
        
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "3"){
                                                            if($wed_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($wed_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                                $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Tuesday

                                    //Wednesday
                                        if($day == "3"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $wed_in = $select_schedule_request[0]->wed_in;
                                            //time out of employee 
                                            $wed_out = $select_schedule_request[0]->wed_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $wed_datetimein = new DateTime($date_in . " " . $wed_in);
                                            //employee datetime out
                                            $wed_datetimeout = new DateTime($date_in . " " . $wed_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $thu_in = $select_schedule_request[0]->thu_in;
                                                $thu_datetimein = new DateTime($date_out . " " . $thu_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($wed_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "4"){
                                                            if($thu_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($thu_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                                $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Wednesday

                                    //Thursday
                                        if($day == "4"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $thu_in = $select_schedule_request[0]->thu_in;
                                            //time out of employee 
                                            $thu_out = $select_schedule_request[0]->thu_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $thu_datetimein = new DateTime($date_in . " " . $thu_in);
                                            //employee datetime out
                                            $thu_datetimeout = new DateTime($date_in . " " . $thu_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $fri_in = $select_schedule_request[0]->fri_in;
                                                $fri_datetimein = new DateTime($date_out . " " . $fri_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($thu_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "5"){
                                                            if($fri_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($fri_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                                $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Thursday

                                    //Friday
                                        if($day == "5"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $fri_in = $select_schedule_request[0]->fri_in;
                                            //time out of employee 
                                            $fri_out = $select_schedule_request[0]->fri_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $fri_datetimein = new DateTime($date_in . " " . $fri_in);
                                            //employee datetime out
                                            $fri_datetimeout = new DateTime($date_in . " " . $fri_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $sat_in = $select_schedule_request[0]->sat_in;
                                                $sat_datetimein = new DateTime($date_out . " " . $sat_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($fri_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "6"){
                                                            if($sat_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($sat_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                                $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of Friday

                                    //Saturday
                                        if($day == "6"){
        
                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                            //time in of employee 
                                            $sat_in = $select_schedule_request[0]->sat_in;
                                            //time out of employee 
                                            $sat_out = $select_schedule_request[0]->sat_out;
        
                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);
        
                                            //employee datetime in
                                            $sat_datetimein = new DateTime($date_in . " " . $sat_in);
                                            //employee datetime out
                                            $sat_datetimeout = new DateTime($date_in . " " . $sat_out);
        
                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){
        
                                                $chkDay = date("N", strtotime($date_out));
        
                                                //get emp time in
                                                $sun_in = $select_schedule_request[0]->sun_in;
                                                $sun_datetimein = new DateTime($date_out . " " . $sun_in);
        
                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                                if($sat_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){
        
                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "7"){
                                                            if($sun_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($sun_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{
        
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                                $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){
        
                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{
        
                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }
        
                                        }
                                    //End of saturday

                                    //Sunday
                                        if($day == "7"){

                                            $overtime = "false";
                                            //date in
                                            $date_in = date("Y-m-d", strtotime($datetimein));
                                            //date out
                                            $date_out = date("Y-m-d", strtotime($datetimeout));

                                            //time in of employee 
                                            $sun_in = $select_schedule_request[0]->sun_in;
                                            //time out of employee 
                                            $sun_out = $select_schedule_request[0]->sun_out;

                                            //datetime in
                                            $_datetimein = new DateTime($datetimein);
                                            //datetime out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //employee datetime in
                                            $sun_datetimein = new DateTime($date_in . " " . $sun_in);
                                            //employee datetime out
                                            $sun_datetimeout = new DateTime($date_in . " " . $sun_out);

                                            //check if the application for overtime is between the next day
                                            if($date_in != $date_out){

                                                $chkDay = date("N", strtotime($date_out));

                                                //get emp time in
                                                $mon_in = $select_schedule_request[0]->mon_in;
                                                $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                                //add 1 day to check if the date out is greater than 1 to date in
                                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                if($sun_datetimeout <= $_datetimein){
                                                    //kapag sobra sa isang araw yung inapply na overtime
                                                    if($date_out > $valid_date_out){

                                                        $message = "Your overtime exceeds 1 day!";
                                                        $error[] = $message;
                                                    }
                                                    else{
                                                        //kapag next day ang inapply na overtime
                                                        if($chkDay == "1"){
                                                            if($mon_in == "00:00:00"){
            
                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                            else if($mon_datetimein < $_datetimeout){
            
                                                                $message = "Overtime cannot be applied!";
                                                                $error[] = $message;
                                                            }
                                                            else{

                                                                $overtime = "true";
                                                                $interval = $_datetimeout->diff($_datetimein);
                                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                            }
                                                        }
                                                    }
                                                }
                                                else{

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                            }
                                            else{
                                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                                if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                                $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    $error[] = $message;
                                                }
                                                else{

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                            }

                                        }
                                    //End of Sunday
                                }
                                else if($select_schedule_request[0]->type == "Flexi Shift"){
    
                                    $overtime = "false";

                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    $_datetimein = new DateTime($datetimein);
                                    
                                    $_datetimeout = new DateTime($datetimeout);

                                    if($date_in != $date_out){

                                        $chkDay = date("N", strtotime($date_out));

                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($date_out > $valid_date_out)
                                        {
                                            $message = "Your overtime exceeds 1 day!";
                                            $error[] = $message;
                                        }
                                        else{
                                            
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    else{
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                else if($select_schedule_request[0]->type == "Free Shift"){
                                    $overtime = "false";

                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    $_datetimein = new DateTime($datetimein);
                                    
                                    $_datetimeout = new DateTime($datetimeout);

                                    if($date_in != $date_out){

                                        $chkDay = date("N", strtotime($date_out));

                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($date_out > $valid_date_out)
                                        {
                                            $message = "Your overtime exceeds 1 day!";
                                            $error[] = $message;
                                        }
                                        else{
                                            
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    else{
                                        $overtime = "true";
                                        $interval = $_datetimeout->diff($_datetimein);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                }
                                if($overtime == "true"){
                                    $insert_query = new OvertimeRecords;
                                    $insert_query->company_id = auth()->user()->company_id;
                                    $insert_query->date_applied = $date_now;
                                    $insert_query->sched_date = $sched_date;
                                    $insert_query->shift_applied = $shift_type;
                                    $insert_query->date_timein = $datetimein;
                                    $insert_query->date_timeout = $datetimeout;
                                    $insert_query->total_hrs = $hour;
                                    $insert_query->reason = $reason;
                                    $insert_query->status = "PENDING";
                                    $insert_query->lu_by = auth()->user()->name;
                                    $insert_query->save();
                                    $message = "Overtime Successfully Applied!";
                                    $success[] = $message;
                                }
                                
                            }    
                            
                            else{

                                $select_schedule_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                                b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                                WHERE a.deleted = '0' AND a.company_id = '".auth()->user()->company_id."'";

                                $select_schedule = DB::connection('mysql3')->select($select_schedule_query);
                                
                                if(!empty($select_schedule)){
                                    
                                    if($select_schedule[0]->type == "Regular Shift"){
                                        $day = date("N", strtotime($request->schedDate)); //converts the date into day
                                                    
                                        //Monday
                                            if($day == "1"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "2"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Monday

                                        //Tuesday
                                            if($day == "2"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "3"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Tuesday

                                        //Wednesday
                                            if($day == "3"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else
                                                        {
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "4"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Wednesday

                                        //Thursday
                                            if($day == "4"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "5"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Thursday
                                        
                                        //Friday
                                            if($day == "5"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "6"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Friday

                                        //Saturday
                                            if($day == "6"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "7"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Saturday

                                        //Sunday
                                            if($day == "7"){

                                                $overtime = "false";

                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $reg_in = $select_schedule[0]->reg_in;
                                                //time out of employee 
                                                $reg_out = $select_schedule[0]->reg_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $reg_datetimein = new DateTime($date_in . " " . $reg_in);
                                                //employee datetime out
                                                $reg_datetimeout = new DateTime($date_in . " " . $reg_out);

                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $reg_in = $select_schedule[0]->reg_in;
                                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($reg_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "1"){
                                                                if($reg_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($reg_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
                                            }
                                        //End of Sunday
                                    }
                                    else if($select_schedule[0]->type == "Irregular Shift"){

                                        $day = date("N", strtotime($request->schedDate)); //converts the date into day

                                        //Monday
                                            if($day == "1"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $mon_in = $select_schedule[0]->mon_in;
                                                //time out of employee 
                                                $mon_out = $select_schedule[0]->mon_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $mon_datetimein = new DateTime($date_in . " " . $mon_in);
                                                //employee datetime out
                                                $mon_datetimeout = new DateTime($date_in . " " . $mon_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $tue_in = $select_schedule[0]->tue_in;
                                                    $tue_datetimein = new DateTime($date_out . " " . $tue_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($mon_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
            
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "2"){
                                                                if($tue_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($tue_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                                    $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of Monday
        
                                        //Tuesday
                                            if($day == "2"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $tue_in = $select_schedule[0]->tue_in;
                                                //time out of employee 
                                                $tue_out = $select_schedule[0]->tue_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $tue_datetimein = new DateTime($date_in . " " . $tue_in);
                                                //employee datetime out
                                                $tue_datetimeout = new DateTime($date_in . " " . $tue_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $wed_in = $select_schedule[0]->wed_in;
                                                    $wed_datetimein = new DateTime($date_out . " " . $wed_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($tue_datetimeout <= $_datetimein){
            
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
            
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "3"){
                                                                if($wed_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($wed_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                                    $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of Tuesday
        
                                        //Wednesday
                                            if($day == "3"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $wed_in = $select_schedule[0]->wed_in;
                                                //time out of employee 
                                                $wed_out = $select_schedule[0]->wed_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $wed_datetimein = new DateTime($date_in . " " . $wed_in);
                                                //employee datetime out
                                                $wed_datetimeout = new DateTime($date_in . " " . $wed_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $thu_in = $select_schedule[0]->thu_in;
                                                    $thu_datetimein = new DateTime($date_out . " " . $thu_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($wed_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "4"){
                                                                if($thu_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($thu_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                                    $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of Wednesday
        
                                        //Thursday
                                            if($day == "4"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $thu_in = $select_schedule[0]->thu_in;
                                                //time out of employee 
                                                $thu_out = $select_schedule[0]->thu_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $thu_datetimein = new DateTime($date_in . " " . $thu_in);
                                                //employee datetime out
                                                $thu_datetimeout = new DateTime($date_in . " " . $thu_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $fri_in = $select_schedule[0]->fri_in;
                                                    $fri_datetimein = new DateTime($date_out . " " . $fri_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($thu_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
            
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "5"){
                                                                if($fri_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($fri_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                                    $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of Thursday
        
                                        //Friday
                                            if($day == "5"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $fri_in = $select_schedule[0]->fri_in;
                                                //time out of employee 
                                                $fri_out = $select_schedule[0]->fri_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $fri_datetimein = new DateTime($date_in . " " . $fri_in);
                                                //employee datetime out
                                                $fri_datetimeout = new DateTime($date_in . " " . $fri_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $sat_in = $select_schedule[0]->sat_in;
                                                    $sat_datetimein = new DateTime($date_out . " " . $sat_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($fri_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
            
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "6"){
                                                                if($sat_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($sat_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                                    $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of Friday
        
                                        //Saturday
                                            if($day == "6"){
            
                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));
            
                                                //time in of employee 
                                                $sat_in = $select_schedule[0]->sat_in;
                                                //time out of employee 
                                                $sat_out = $select_schedule[0]->sat_out;
            
                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);
            
                                                //employee datetime in
                                                $sat_datetimein = new DateTime($date_in . " " . $sat_in);
                                                //employee datetime out
                                                $sat_datetimeout = new DateTime($date_in . " " . $sat_out);
            
                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){
            
                                                    $chkDay = date("N", strtotime($date_out));
            
                                                    //get emp time in
                                                    $sun_in = $select_schedule[0]->sun_in;
                                                    $sun_datetimein = new DateTime($date_out . " " . $sun_in);
            
                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
            
                                                    if($sat_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){
            
                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "7"){
                                                                if($sun_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($sun_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{
            
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                                    $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){
            
                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{
            
                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }
            
                                            }
                                        //End of saturday

                                        //Sunday
                                            if($day == "7"){

                                                $overtime = "false";
                                                //date in
                                                $date_in = date("Y-m-d", strtotime($datetimein));
                                                //date out
                                                $date_out = date("Y-m-d", strtotime($datetimeout));

                                                //time in of employee 
                                                $sun_in = $select_schedule[0]->sun_in;
                                                //time out of employee 
                                                $sun_out = $select_schedule[0]->sun_out;

                                                //datetime in
                                                $_datetimein = new DateTime($datetimein);
                                                //datetime out
                                                $_datetimeout = new DateTime($datetimeout);

                                                //employee datetime in
                                                $sun_datetimein = new DateTime($date_in . " " . $sun_in);
                                                //employee datetime out
                                                $sun_datetimeout = new DateTime($date_in . " " . $sun_out);

                                                //check if the application for overtime is between the next day
                                                if($date_in != $date_out){

                                                    $chkDay = date("N", strtotime($date_out));

                                                    //get emp time in
                                                    $mon_in = $select_schedule[0]->mon_in;
                                                    $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                                    //add 1 day to check if the date out is greater than 1 to date in
                                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                                    if($sun_datetimeout <= $_datetimein){
                                                        //kapag sobra sa isang araw yung inapply na overtime
                                                        if($date_out > $valid_date_out){

                                                            $message = "Your overtime exceeds 1 day!";
                                                            $error[] = $message;
                                                        }
                                                        else{
                                                            //kapag next day ang inapply na overtime
                                                            if($chkDay == "1"){
                                                                if($mon_in == "00:00:00"){
                
                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                                else if($mon_datetimein < $_datetimeout){
                
                                                                    $message = "Overtime cannot be applied!";
                                                                    $error[] = $message;
                                                                }
                                                                else{

                                                                    $overtime = "true";
                                                                    $interval = $_datetimeout->diff($_datetimein);
                                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else{

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                }
                                                else{
                                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                                    if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                                    $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                                        $message = "Overtime cannot be applied!";
                                                        $error[] = $message;
                                                    }
                                                    else{

                                                        $overtime = "true";
                                                        $interval = $_datetimeout->diff($_datetimein);
                                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                    }
                                                }

                                            }
                                        //End of Sunday
                                    }
                                    else if($select_schedule[0]->type == "Flexi Shift"){
        
                                        $overtime = "false";

                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                        $_datetimein = new DateTime($datetimein);
                                        
                                        $_datetimeout = new DateTime($datetimeout);
        
                                        if($date_in != $date_out){
        
                                            $chkDay = date("N", strtotime($date_out));
        
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                            if($date_out > $valid_date_out)
                                            {
                                                $message = "Your overtime exceeds 1 day!";
                                                $error[] = $message;
                                            }
                                            else{
                                                
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                        else{
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    else if($select_schedule[0]->type == "Free Shift"){
                                        $overtime = "false";

                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        
                                        $date_out = date("Y-m-d", strtotime($datetimeout));
        
                                        $_datetimein = new DateTime($datetimein);
                                        
                                        $_datetimeout = new DateTime($datetimeout);
        
                                        if($date_in != $date_out){
        
                                            $chkDay = date("N", strtotime($date_out));
        
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));
        
                                            if($date_out > $valid_date_out)
                                            {
                                                $message = "Your overtime exceeds 1 day!";
                                                $error[] = $message;
                                            }
                                            else{
                                                
                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                        else{
                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }
                                    if($overtime == "true"){
                                        $insert_query = new OvertimeRecords;
                                        $insert_query->company_id = auth()->user()->company_id;
                                        $insert_query->date_applied = $date_now;
                                        $insert_query->sched_date = $sched_date;
                                        $insert_query->shift_applied = $shift_type;
                                        $insert_query->date_timein = $datetimein;
                                        $insert_query->date_timeout = $datetimeout;
                                        $insert_query->total_hrs = $hour;
                                        $insert_query->reason = $reason;
                                        $insert_query->status = "PENDING";
                                        $insert_query->lu_by = auth()->user()->name;
                                        $insert_query->save();
                                        $message = "Overtime Successfully Applied!";
                                        $success[] = $message;
                                    }
                                }
                                
                            }
                        }
                    }
                }
                else{
                    $message = "No Alteration found for this employee!";
                    $error[] = $message;
                }

            }else{
                $message = "No Alteration found for this employee!";
                $error[] = $message;
            }
        }
        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
        
    }
    public function filter_dates(Request $request)
    {   
        if($request->startDate != "" && $request->endDate != ""){

            $start_date = date("Y-m-d",strtotime($request->startDate));
            $end_date = date("Y-m-d",strtotime($request->endDate));
            $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE (sched_date BETWEEN '".$start_date."' AND '".$end_date."') AND company_id = '".auth()->user()->company_id."' ORDER BY created_at DESC");
        
        }else if($request->status != ""){
            if($request->status == "ALL"){
                $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."' ORDER BY created_at DESC");
            }else{
                $status = $request->status;
            $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE status = '".$status."' AND company_id = '".auth()->user()->company_id."' ORDER BY created_at DESC");
            }
            
        }else{
            $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."' ORDER BY created_at DESC");
        }
        
        $data = "";
        $data = '<div id="divOvertimeRecord"class="table-responsive">
        <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-hover" style="width:100%">
            <col>
            <colgroup span="2"></colgroup>
            <colgroup span="2"></colgroup>
            <thead>
                <tr class="header" style="background:#f7f7f7;">
                    <th colspan="10" class="text-center">OVERTIME RECORDS</th>
                </tr>
                <tr>
                    <th rowspan="2">Date Applied</th>
                    <th rowspan="2">Applied Time In</th>
                    <th rowspan="2">Applied Time Out</th>
                    <th rowspan="2">Shift Type</th>
                    <th rowspan="2" >Reason</th>
                    <th rowspan="2" >Total Hours</th>
                    <th colspan="2" scope="colgroup" style="">Approval History</th>
                    <tr>
                        
                        <th scope="col">Level 1</th>
                        <th scope="col" >Level 2</th>
                        <th style="border-top:0px;">
                            Status
                        </th>
                        <th style="border-top:0px;">
                            Actions
                        </th>
                    </tr>
                </tr>          
            </thead>
            <tbody>';

            $counter = 1;
            if(count($view_overtime_records) > 0){
                foreach($view_overtime_records as $field){
                    $data .= "<tr>";
                        $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($field->date_applied))."</a>
                        <br>
                        <small><a id='dayApplied".$counter."'>".date("l",strtotime($field->date_applied))."</a></small>
                        </td>";

                        $data .= "<td><a id='date_timein".$counter."'>".date("F j Y",strtotime($field->date_timein))."</a>
                        <br>
                        <a id='date_timein_clck".$counter."'>".date("h:i A",strtotime($field->date_timein))."</a>
                        </td>";
                        $data .= "<td>
                        <a id='date_timeout".$counter."'>".date("F j Y",strtotime($field->date_timeout))."</a>
                        <br>
                        <a id='date_timeout_clck".$counter."'>".date("h:i A",strtotime($field->date_timeout))."</a>
                        </td>";
                        $data .= "<td><a id='shift_applied".$counter."'>".$field->shift_applied."</a></td>";
                        $data .= "<td><a id='reason".$counter."'>".$field->reason."</a></td>";
                        $data .= "<td><a id='total_hrs".$counter."'>".$field->total_hrs."</a></td>";

                        if($field->approved_1_id != ""){
                            $data .= "<td><a id='approved_1_id".$counter."'>".$field->approved_1_id."</a></td>";
                        }else{
                            $data .= "<td></td>";
                        }

                        if($field->approved_2_id != ""){
                            $data .= "<td><a id='approved_2_id".$counter."'>".$field->approved_2_id."</a></td>";
                        }else{
                            $data .= "<td></td>";
                        }

                        if($field->status == "APPROVED")
                        {
                            $data .= "<td style='color:#28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                        }
                        else if ($field->status == "CANCELLED"){
                            $data .= "<td style='color:#dc3545;'><i class='icon-right fa fa-times-circle'></i><b>CANCELLED</b></td>";
                        }
                        else if ($field->status == "PENDING")
                        {
                            $data .="<td style='color:#E87B15;'><i class='icon-right fa fa-question-circle'></i><b>PENDING</b></td>";
                        }
                        else{
                            $data .="<td></td>";
                        }

                        if($field->status == "PENDING"){
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
