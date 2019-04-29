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

        $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."' ORDER BY sched_date DESC");
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
        
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

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

        //If Overtime is already exists
        if(!empty($check_ot)){
            
            $message = "Your overtime already exists!";

        //If there's no overtime matched for particular date
        }else{

            //Employee Schedule Request
            $select_schedule_request_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
            b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
            WHERE a.deleted = '0' AND '" . $sched_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '".auth()->user()->company_id."'";
            $select_schedule_request = DB::connection('mysql3')->select($select_schedule_request_query);

            if(!empty($select_schedule_request)){
                if($select_schedule_request[0]->type == "Regular Shift"){
                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "2"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                    
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "3"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "4"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                    
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "5"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "7"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($reg_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "1"){
                                            if($reg_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $reg_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
                                }
                                else{

                                    $overtime = "true";
                                    $interval = $_datetimeout->diff($_datetimein);
                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                }
                            }
                        }
                    //End of sunday
                }
                else if($select_schedule_request[0]->type == "Irregular Shift"){
                    
                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

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
                                $tue_in = $select_schedule_request[0]->mon_in;
                                $tue_datetimein = new DateTime($date_out . " " . $tue_in);

                                //add 1 day to check if the date out is greater than 1 to date in
                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                if($mon_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($tue_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($tue_datetimein > $mon_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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
                                $wed_in = $select_schedule_request[0]->mon_in;
                                $wed_datetimein = new DateTime($date_out . " " . $wed_in);

                                //add 1 day to check if the date out is greater than 1 to date in
                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                if($tue_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($wed_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($wed_datetimein > $tue_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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
                                $thu_in = $select_schedule_request[0]->mon_in;
                                $thu_datetimein = new DateTime($date_out . " " . $thu_in);

                                //add 1 day to check if the date out is greater than 1 to date in
                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                if($wed_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($thu_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($thu_datetimein > $wed_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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
                                $fri_in = $select_schedule_request[0]->mon_in;
                                $fri_datetimein = new DateTime($date_out . " " . $fri_in);

                                //add 1 day to check if the date out is greater than 1 to date in
                                $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                if($thu_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){
                                        $message = "Overtime cannot be applied!";
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($fri_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($fri_datetimein > $thu_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($fri_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($sat_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($sat_datetimein > $fri_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($sat_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                        
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "6"){
                                            if($sun_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($sun_datetimein > $sat_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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

                                if($sun_datetimeout < $_datetimein){
                                    //kapag sobra sa isang araw yung inapply na overtime
                                    if($date_out > $valid_date_out){

                                        $message = "Overtime cannot be applied!";
                                       
                                    }
                                    else{
                                        //kapag next day ang inapply na overtime
                                        if($chkDay == "1"){
                                            if($mon_in == "00:00:00"){

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else if($mon_datetimein > $sun_datetimeout){

                                                $message = "Overtime cannot be applied!";
                                                
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
                                    
                                }
                            }
                            else{
                                //kapag tumama sa inapplyang overtime yung sched time niya 
                                if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                    $message = "Overtime cannot be applied!";
                                    
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
                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day
 
                    $overtime = "false";

                    //datetime in
                    $_datetimein = new DateTime($datetimein);
                    //datetime out
                    $_datetimeout = new DateTime($datetimeout);

                    //check if the application for overtime is between the next day
                    if($date_in != $date_out){

                        $chkDay = date("N", strtotime($date_out));

                        //add 1 day to check if the date out is greater than 1 to date in
                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                        //kapag sobra sa isang araw yung inapply na overtime
                        if($date_out > $valid_date_out){

                            $message = "Overtime cannot be applied!";
                            
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
                    $message = "No Schedule Request Found: Free Shift";
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
                    $message = "Overtime successfully apply!";
                }
            }
            
            //Employee Schedule
            else{

                $select_schedule_query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                WHERE a.deleted = '0' AND a.company_id = '".auth()->user()->company_id."'";

                $select_schedule = DB::connection('mysql3')->select($select_schedule_query);

                if(!empty($select_schedule)){
                    if($select_schedule[0]->type == "Regular Shift"){
                        //This will convert schedule date to day
                        $day = date("N", strtotime($request->input('schedDate')));

                        //Monday
                            if($day == "1"){
                                //Monday Variables
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
                                //Monday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //Get Employee Timein
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "2"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
                                        $message = "Overtime cannot be applied!";
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
                            else if($day == "2"){
                                //Tuesday Variables
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
                                //Tuesday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //get emp time in
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "3"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";

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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                        $message = "Overtime cannot be applied!";
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
                            else if($day == "3"){
                                //Wednesday Variables
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

                                //Wednesday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //get emp time in
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "4"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){
                                                    $message = "Overtime cannot be applied!";
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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
                                        $message = "Overtime cannot be applied!";
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
                            else if($day == "4"){
                                //Thursday Variables
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
                                //Thursday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //get emp time in
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "5"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";
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
                            else if($day == "5"){
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

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";
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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                        $message = "Overtime cannot be applied!";

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
                            else if($day == "6"){
                                //Saturday Variables
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
                                //Saturday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //get emp time in
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "7"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";
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

                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                        $message = "Overtime cannot be applied!";
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
                            else if($day == "7"){
                                //Sunday Variables
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
                                //Sunday Variables

                                if($date_in != $date_out){

                                    $chkDay = date("N", strtotime($date_out));

                                    //get emp time in
                                    $reg_in = $select_schedule[0]->reg_in;
                                    $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($reg_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){
                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "1"){
                                                if($reg_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $reg_datetimeout){

                                                    $message = "Overtime cannot be applied!";

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

                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                    $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){

                                        $message = "Overtime cannot be applied!";
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

                        //This will convert schedule date to day
                        $day = date("N", strtotime($request->input('schedDate')));

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
                                    $tue_in = $select_schedule[0]->mon_in;
                                    $tue_datetimein = new DateTime($date_out . " " . $tue_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($mon_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($tue_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($tue_datetimein > $mon_datetimeout){

                                                    $message = "Overtime cannot be applied!";

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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                    $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){

                                        $message = "Overtime cannot be applied!";
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
                                    $wed_in = $select_schedule[0]->mon_in;
                                    $wed_datetimein = new DateTime($date_out . " " . $wed_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($tue_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                            
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($wed_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($wed_datetimein > $tue_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                    $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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
                                    $thu_in = $select_schedule[0]->mon_in;
                                    $thu_datetimein = new DateTime($date_out . " " . $thu_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($wed_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                           
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($thu_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($thu_datetimein > $wed_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                        
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                    $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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
                                    $fri_in = $select_schedule[0]->mon_in;
                                    $fri_datetimein = new DateTime($date_out . " " . $fri_in);

                                    //add 1 day to check if the date out is greater than 1 to date in
                                    $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                    if($thu_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";

                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($fri_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($fri_datetimein > $thu_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                        
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                    $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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

                                    if($fri_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                            
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($sat_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($sat_datetimein > $fri_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                        
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                    $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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

                                    if($sat_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                            
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "6"){
                                                if($sun_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($sun_datetimein > $sat_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                        
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                    $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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

                                    if($sun_datetimeout < $_datetimein){
                                        //kapag sobra sa isang araw yung inapply na overtime
                                        if($date_out > $valid_date_out){

                                            $message = "Overtime cannot be applied!";
                                            
                                        }
                                        else{
                                            //kapag next day ang inapply na overtime
                                            if($chkDay == "1"){
                                                if($mon_in == "00:00:00"){

                                                    $overtime = "true";
                                                    $interval = $_datetimeout->diff($_datetimein);
                                                    $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                                }
                                                else if($mon_datetimein > $sun_datetimeout){

                                                    $message = "Overtime cannot be applied!";
                                                    
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
                                        
                                    }
                                }
                                else{
                                    //kapag tumama sa inapplyang overtime yung sched time niya 
                                    if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                    $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                        $message = "Overtime cannot be applied!";
                                        
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
                        $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day
    
                        $overtime = "false";

                        //date in
                        $date_in = date("Y-m-d", strtotime($datetimein));
                        //date out
                        $date_out = date("Y-m-d", strtotime($datetimeout));

                        //datetime in
                        $_datetimein = new DateTime($datetimein);
                        //datetime out
                        $_datetimeout = new DateTime($datetimeout);

                        //check if the application for overtime is between the next day
                        if($date_in != $date_out){

                            $chkDay = date("N", strtotime($date_out));

                            //add 1 day to check if the date out is greater than 1 to date in
                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                            //kapag sobra sa isang araw yung inapply na overtime
                            if($date_out > $valid_date_out){

                                $message = "Overtime cannot be applied!";
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
                        $message = "Schedule: Free Shift";
                    }
                    if($overtime == true){
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
                    }
                }
                
            }
        }
        echo json_encode($message);

    }
    public function filter_dates(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $start_date = date("Y-m-d", strtotime("$start_date"));
        $end_date = date("Y-m-d", strtotime("$end_date"));

        $message = "Success!";
        echo json_encode($message);
    }
}
