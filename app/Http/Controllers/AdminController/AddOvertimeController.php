<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OvertimeRecords;
use DB;
use DateTime;

class AddOvertimeController extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.addovertime.addovertime');
    }
    
    public function checkOvertime(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        //date now
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

        //date to alter
        $sched_date = date("Y-m-d", strtotime($request->input('schedDate')));
        //time in
        $timein = date("H:i:s", strtotime($request->input('timeIn')));
        //date time in
        $datetimein = date("Y-m-d H:i:s", strtotime("$sched_date $timein"));
        //time out
        $datetimeout = date("Y-m-d H:i:s", strtotime($request->input('timeOut')));

        if($request->input('schedDate') == ""){

            $message = "Schedule Date field is required!";
            $error[] = $message;
        }
        else if($request->input('timeIn') == ""){

            $message = "Time In field is required!";
            $error[] = $message;
        }
        else if($request->input('timeOut') == ""){

            $message = "Time Out field is required!";
            $error[] = $message;
        }
        else if($datetimeout <= $datetimein){

            $message = "Date time out must greater than Date time in!";
            $error[] = $message;
        }
        else if($request->input('cmbShift') == ""){

            $message = "Please select Shift Type!";
            $error[] = $message;
        }
        else if($request->input('txtReason') == ""){

            $message = "Reason field is required!";
            $error[] = $message;
        }
        else if($request->selectCount == 0){

            $message = "Please select an employee!";
            $error[] = $message;
        }
        else{
            $message = "1";
            $success[] = $message;
        }

        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function addOvertime(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        $total_hours = 0.0;
        $hour = 0.0;
        $total_flexihrs = 0.0;

        //date now
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

        //date to overtime
        $sched_date = date("Y-m-d", strtotime($request->input('schedDate')));
        //time in
        $timein = date("H:i:s", strtotime($request->input('timeIn')));
        //date time in
        $datetimein = date("Y-m-d H:i:s", strtotime("$sched_date $timein"));
        //time out
        $datetimeout = date("Y-m-d H:i:s", strtotime($request->input('timeOut')));

        for($i = 1; $i <= $request->input('tblCount'); $i++){
            if($request->input('empList' . $i)){

                if($request->input('empList' . $i) == "on"){

                    // check if the date want to overtime is already exist
                    $CHECK_OT_QUERY = "SELECT company_id, sched_date, status FROM overtime_records WHERE sched_date = '" . $sched_date . "' AND company_id = '" . $request->input('empListVal' . $i). "' AND status <> 'CANCELLED'";
                    $CHECK_OT = DB::connection('mysql')->select($CHECK_OT_QUERY);

                    if(!empty($CHECK_OT)){

                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime already exist";
                        $error[] = $message;
                    }
                    //check employee sched request
                    else{

                        $SELECT_SCHEDULE_REQUEST_QUERY = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                        b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                        b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                        WHERE a.deleted = '0' AND '" . $sched_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '" . $request->input('empListVal' . $i) . "'";
                        $SELECT_SCHEDULE_REQUEST = DB::connection('mysql3')->select($SELECT_SCHEDULE_REQUEST_QUERY);

                        if(!empty($SELECT_SCHEDULE_REQUEST)){

                            //reg
                            if($SELECT_SCHEDULE_REQUEST[0]->type == "Regular Shift"){

                                $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day
                                    
                                    //monday
                                    if($day == "1"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end monday

                                    //tuesday
                                    if($day == "2"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end tuesday

                                    //wednesday
                                    if($day == "3"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end wednesday

                                    //thursday
                                    if($day == "4"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end thursday

                                    //friday
                                    if($day == "5"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end friday

                                    //saturday
                                    if($day == "6"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end saturday

                                    //sunday
                                    if($day == "7"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE_REQUEST[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE_REQUEST[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end sunday
                                

                            }
                            //irreg
                            else if($SELECT_SCHEDULE_REQUEST[0]->type == "Irregular Shift"){

                                $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                //monday
                                if($day == "1"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $mon_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                    //time out of employee 
                                    $mon_out = $SELECT_SCHEDULE_REQUEST[0]->mon_out;

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
                                        $tue_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                        $tue_datetimein = new DateTime($date_out . " " . $tue_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($mon_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
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
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                        $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day monday

                                //tuesday
                                if($day == "2"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $tue_in = $SELECT_SCHEDULE_REQUEST[0]->tue_in;
                                    //time out of employee 
                                    $tue_out = $SELECT_SCHEDULE_REQUEST[0]->tue_out;

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
                                        $wed_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                        $wed_datetimein = new DateTime($date_out . " " . $wed_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($tue_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
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
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                        $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day tuesday

                                //wednesday
                                if($day == "3"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $wed_in = $SELECT_SCHEDULE_REQUEST[0]->wed_in;
                                    //time out of employee 
                                    $wed_out = $SELECT_SCHEDULE_REQUEST[0]->wed_out;

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
                                        $thu_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                        $thu_datetimein = new DateTime($date_out . " " . $thu_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($wed_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
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
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                        $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day wednesday

                                //thursday
                                if($day == "4"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $thu_in = $SELECT_SCHEDULE_REQUEST[0]->thu_in;
                                    //time out of employee 
                                    $thu_out = $SELECT_SCHEDULE_REQUEST[0]->thu_out;

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
                                        $fri_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                        $fri_datetimein = new DateTime($date_out . " " . $fri_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($thu_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
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
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                        $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day thursday

                                //friday
                                if($day == "5"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $fri_in = $SELECT_SCHEDULE_REQUEST[0]->fri_in;
                                    //time out of employee 
                                    $fri_out = $SELECT_SCHEDULE_REQUEST[0]->fri_out;

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
                                        $sat_in = $SELECT_SCHEDULE_REQUEST[0]->sat_in;
                                        $sat_datetimein = new DateTime($date_out . " " . $sat_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($fri_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                    else if($sat_datetimein > $fri_datetimeout){
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                        $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day friday

                                //saturday
                                if($day == "6"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $sat_in = $SELECT_SCHEDULE_REQUEST[0]->sat_in;
                                    //time out of employee 
                                    $sat_out = $SELECT_SCHEDULE_REQUEST[0]->sat_out;

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
                                        $sun_in = $SELECT_SCHEDULE_REQUEST[0]->sun_in;
                                        $sun_datetimein = new DateTime($date_out . " " . $sun_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($sat_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
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
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                        $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day saturday

                                //sunday
                                if($day == "7"){

                                    $overtime = "false";
                                    //date in
                                    $date_in = date("Y-m-d", strtotime($datetimein));
                                    //date out
                                    $date_out = date("Y-m-d", strtotime($datetimeout));

                                    //time in of employee 
                                    $sun_in = $SELECT_SCHEDULE_REQUEST[0]->sun_in;
                                    //time out of employee 
                                    $sun_out = $SELECT_SCHEDULE_REQUEST[0]->sun_out;

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
                                        $mon_in = $SELECT_SCHEDULE_REQUEST[0]->mon_in;
                                        $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                        //add 1 day to check if the date out is greater than 1 to date in
                                        $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                        if($sun_datetimeout < $_datetimein){
                                            //kapag sobra sa isang araw yung inapply na overtime
                                            if($date_out > $valid_date_out){

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                    else if($mon_datetimein > $sun_datetimeout){
    
                                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                    }
                                    else{
                                        //kapag tumama sa inapplyang overtime yung sched time niya 
                                        if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                        $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                            $error[] = $message;
                                        }
                                        else{

                                            $overtime = "true";
                                            $interval = $_datetimeout->diff($_datetimein);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                    }

                                }
                                //end day sunday
                            }
                            //flexi
                            else if($SELECT_SCHEDULE_REQUEST[0]->type == "Flexi Shift"){

                                //$total_flexihrs = $SELECT_SCHEDULE_REQUEST[0]->flexihrs;

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

                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                            //free
                            else if($SELECT_SCHEDULE_REQUEST[0]->type == "Free Shift"){
                            }
                            
                            if($overtime == "true"){

                                $insert_query = new OvertimeRecords;
                                $insert_query->company_id = $request->input('empListVal' . $i);
                                $insert_query->date_applied = $date_now;
                                $insert_query->sched_date = $sched_date;
                                $insert_query->shift_applied = $request->input('cmbShift');
                                $insert_query->date_timein = $datetimein;
                                $insert_query->date_timeout = $datetimeout;
                                $insert_query->total_hrs = $hour;
                                $insert_query->reason = $request->input('txtReason');
                                $insert_query->status = "APPROVED";
                                $insert_query->approved_1 = 1;
                                $insert_query->approved_2 = 1;
                                $insert_query->approved_1_id = auth()->user()->company_id;
                                $insert_query->approved_2_id = auth()->user()->company_id;
                                $insert_query->lu_by = auth()->user()->name;
                                $insert_query->save();

                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime successfully apply!";
                                $success[] = $message;
                            }
                        }
                        //check employee request
                        else{
                            
                            $SELECT_SCHEDULE_QUERY = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                            WHERE a.deleted = '0' AND a.company_id = '".$request->input('empListVal' . $i) . "'";
                            $SELECT_SCHEDULE = DB::connection('mysql3')->select($SELECT_SCHEDULE_QUERY);

                            if(!empty($SELECT_SCHEDULE)){
                                
                                //reg
                                if($SELECT_SCHEDULE[0]->type == "Regular Shift"){

                                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day
                                    
                                    //monday
                                    if($day == "1"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end monday

                                    //tuesday
                                    if($day == "2"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end tuesday

                                    //wednesday
                                    if($day == "3"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end wednesday

                                    //thursday
                                    if($day == "4"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end thursday

                                    //friday
                                    if($day == "5"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end friday

                                    //saturday
                                    if($day == "6"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end saturday

                                    //sunday
                                    if($day == "7"){

                                        $overtime = "false";

                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                        //time out of employee 
                                        $reg_out = $SELECT_SCHEDULE[0]->reg_out;

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
                                            $reg_in = $SELECT_SCHEDULE[0]->reg_in;
                                            $reg_datetimein = new DateTime($date_out . " " . $reg_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($reg_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($tue_datetimein > $reg_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $reg_datetimeout && $_datetimeout > $reg_datetimein || $_datetimein < $reg_datetimein &&  $_datetimeout > $reg_datetimeout || 
                                            $_datetimein > $reg_datetimein && $_datetimeout > $reg_datetimeout && $_datetimein < $reg_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }
                                    }
                                    //end sunday
                                }

                                //irreg
                                else if($SELECT_SCHEDULE[0]->type == "Irregular Shift"){

                                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                    //monday
                                    if($day == "1"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $mon_in = $SELECT_SCHEDULE[0]->mon_in;
                                        //time out of employee 
                                        $mon_out = $SELECT_SCHEDULE[0]->mon_out;

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
                                            $tue_in = $SELECT_SCHEDULE[0]->mon_in;
                                            $tue_datetimein = new DateTime($date_out . " " . $tue_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($mon_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $mon_datetimeout && $_datetimeout > $mon_datetimein || $_datetimein < $mon_datetimein &&  $_datetimeout > $mon_datetimeout || 
                                            $_datetimein > $mon_datetimein && $_datetimeout > $mon_datetimeout && $_datetimein < $mon_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day monday

                                    //tuesday
                                    if($day == "2"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $tue_in = $SELECT_SCHEDULE[0]->tue_in;
                                        //time out of employee 
                                        $tue_out = $SELECT_SCHEDULE[0]->tue_out;

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
                                            $wed_in = $SELECT_SCHEDULE[0]->mon_in;
                                            $wed_datetimein = new DateTime($date_out . " " . $wed_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($tue_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $tue_datetimeout && $_datetimeout > $tue_datetimein || $_datetimein < $tue_datetimein &&  $_datetimeout > $tue_datetimeout || 
                                            $_datetimein > $tue_datetimein && $_datetimeout > $tue_datetimeout && $_datetimein < $tue_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day tuesday

                                    //wednesday
                                    if($day == "3"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $wed_in = $SELECT_SCHEDULE[0]->wed_in;
                                        //time out of employee 
                                        $wed_out = $SELECT_SCHEDULE[0]->wed_out;

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
                                            $thu_in = $SELECT_SCHEDULE[0]->mon_in;
                                            $thu_datetimein = new DateTime($date_out . " " . $thu_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($wed_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $wed_datetimeout && $_datetimeout > $wed_datetimein || $_datetimein < $wed_datetimein &&  $_datetimeout > $wed_datetimeout || 
                                            $_datetimein > $wed_datetimein && $_datetimeout > $wed_datetimeout && $_datetimein < $wed_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day wednesday

                                    //thursday
                                    if($day == "4"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $thu_in = $SELECT_SCHEDULE[0]->thu_in;
                                        //time out of employee 
                                        $thu_out = $SELECT_SCHEDULE[0]->thu_out;

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
                                            $fri_in = $SELECT_SCHEDULE[0]->mon_in;
                                            $fri_datetimein = new DateTime($date_out . " " . $fri_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($thu_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $thu_datetimeout && $_datetimeout > $thu_datetimein || $_datetimein < $thu_datetimein &&  $_datetimeout > $thu_datetimeout || 
                                            $_datetimein > $thu_datetimein && $_datetimeout > $thu_datetimeout && $_datetimein < $thu_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day thursday

                                    //friday
                                    if($day == "5"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $fri_in = $SELECT_SCHEDULE[0]->fri_in;
                                        //time out of employee 
                                        $fri_out = $SELECT_SCHEDULE[0]->fri_out;

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
                                            $sat_in = $SELECT_SCHEDULE[0]->sat_in;
                                            $sat_datetimein = new DateTime($date_out . " " . $sat_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($fri_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($sat_datetimein > $fri_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $fri_datetimeout && $_datetimeout > $fri_datetimein || $_datetimein < $fri_datetimein &&  $_datetimeout > $fri_datetimeout || 
                                            $_datetimein > $fri_datetimein && $_datetimeout > $fri_datetimeout && $_datetimein < $fri_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day friday


                                    //saturday
                                    if($day == "6"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $sat_in = $SELECT_SCHEDULE[0]->sat_in;
                                        //time out of employee 
                                        $sat_out = $SELECT_SCHEDULE[0]->sat_out;

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
                                            $sun_in = $SELECT_SCHEDULE[0]->sun_in;
                                            $sun_datetimein = new DateTime($date_out . " " . $sun_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($sat_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                    $error[] = $message;
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
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $sat_datetimeout && $_datetimeout > $sat_datetimein || $_datetimein < $sat_datetimein &&  $_datetimeout > $sat_datetimeout || 
                                            $_datetimein > $sat_datetimein && $_datetimeout > $sat_datetimeout && $_datetimein < $sat_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day saturday

                                    //sunday
                                    if($day == "7"){

                                        $overtime = "false";
                                        //date in
                                        $date_in = date("Y-m-d", strtotime($datetimein));
                                        //date out
                                        $date_out = date("Y-m-d", strtotime($datetimeout));

                                        //time in of employee 
                                        $sun_in = $SELECT_SCHEDULE[0]->sun_in;
                                        //time out of employee 
                                        $sun_out = $SELECT_SCHEDULE[0]->sun_out;

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
                                            $mon_in = $SELECT_SCHEDULE[0]->mon_in;
                                            $mon_datetimein = new DateTime($date_out . " " . $mon_in);

                                            //add 1 day to check if the date out is greater than 1 to date in
                                            $valid_date_out = date("Y-m-d", strtotime("+1 days", strtotime($sched_date)));

                                            if($sun_datetimeout < $_datetimein){
                                                //kapag sobra sa isang araw yung inapply na overtime
                                                if($date_out > $valid_date_out){

                                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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
                                                        else if($mon_datetimein > $sun_datetimeout){
        
                                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                        }
                                        else{
                                            //kapag tumama sa inapplyang overtime yung sched time niya 
                                            if($_datetimeout < $sun_datetimeout && $_datetimeout > $sun_datetimein || $_datetimein < $sun_datetimein &&  $_datetimeout > $sun_datetimeout || 
                                            $_datetimein > $sun_datetimein && $_datetimeout > $sun_datetimeout && $_datetimein < $sun_datetimeout){
    
                                                $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
                                                $error[] = $message;
                                            }
                                            else{

                                                $overtime = "true";
                                                $interval = $_datetimeout->diff($_datetimein);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                        }

                                    }
                                    //end day sunday

                                }
                                //flexi
                                else if($SELECT_SCHEDULE[0]->type == "Flexi Shift"){

                                    //$total_flexihrs = $SELECT_SCHEDULE[0]->flexihrs;

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

                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime cannot be applied!";
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

                                //free
                                else if($SELECT_SCHEDULE[0]->type == "Free Shift"){
                                }
                                
                                if($overtime == "true"){

                                    $insert_query = new OvertimeRecords;
                                    $insert_query->company_id = $request->input('empListVal' . $i);
                                    $insert_query->date_applied = $date_now;
                                    $insert_query->sched_date = $sched_date;
                                    $insert_query->shift_applied = $request->input('cmbShift');
                                    $insert_query->date_timein = $datetimein;
                                    $insert_query->date_timeout = $datetimeout;
                                    $insert_query->total_hrs = $hour;
                                    $insert_query->reason = $request->input('txtReason');
                                    $insert_query->status = "APPROVED";
                                    $insert_query->approved_1 = 1;
                                    $insert_query->approved_2 = 1;
                                    $insert_query->approved_1_id = auth()->user()->company_id;
                                    $insert_query->approved_2_id = auth()->user()->company_id;
                                    $insert_query->lu_by = auth()->user()->name;
                                    $insert_query->save();

                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Overtime successfully apply!";
                                    $success[] = $message;
                                }
                            }
                        }
                    }
                }
            }
        }

        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function searchOvertimeRecord(Request $request)
    {
        $data = "";

        $SEARCH_OT_RECORD_QUERY = "SELECT id, date_applied, company_id, emp_name, shift_applied, date_timein, date_timeout, total_hrs, status, approved_1, approved_2, payroll_register_number FROM view_overtime_records";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_OT_RECORD_QUERY .= " ORDER BY date_applied, id DESC";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND deleted = '0' ORDER BY date_applied, id DESC";
                }
                else{
                    $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                    }
                    else{
                        $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_OT_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC"; 
                        }
                    }
                }
            }
        }

        $SEARCH_OT_RECORD = DB::connection('mysql')->select($SEARCH_OT_RECORD_QUERY);

        $data = '<table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                                <th colspan="10" class="text-center">OVERTIME RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Applied Time In</th>
                            <th>Applied Time Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                </thead>
                <tbody>';
        $i = 1;
        if(count($SEARCH_OT_RECORD) > 0){          
            foreach($SEARCH_OT_RECORD as $otr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $otr->company_id . '</td>';
                $data .= '<td>' . $otr->emp_name . '</td>';
                $data .= '<td>' . $otr->date_applied . '</td>';             
                $data .= '<td>' . $otr->date_timein . '</td>';
                $data .= '<td>' . $otr->date_timeout . '</td>';
                $data .= '<td>' . $otr->total_hrs . '</td>';

                //check status
                if($otr->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($otr->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($otr->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($otr->status != "CANCELLED" && $otr->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $otr->id .'" value="Cancel Alteration"></td>';
                }
                else{
                    $data .= '<td style="width:auto";></td>';
                }
                //end button cancel

                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
            </table>';
        }
        echo $data;
    }

    public function getOvertimeRecord()
    {
        $data = "";

        $GET_OT_RECORD_QUERY = "SELECT id, date_applied, company_id, emp_name, shift_applied, date_timein, date_timeout, total_hrs, status, approved_1, approved_2, payroll_register_number FROM view_overtime_records WHERE deleted = '0' ORDER BY date_applied, id DESC";
        $GET_OT_RECORD = DB::connection('mysql')->select($GET_OT_RECORD_QUERY);

        $data = '<table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-striped" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="10" class="text-center">OVERTIME RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Applied Time In</th>
                            <th>Applied Time Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                </thead>
                <tbody>';
        $i = 1;
        if(count($GET_OT_RECORD) > 0){          
            foreach($GET_OT_RECORD as $otr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $otr->company_id . '</td>';
                $data .= '<td>' . $otr->emp_name . '</td>';
                $data .= '<td>' . $otr->date_applied . '</td>';             
                $data .= '<td>' . $otr->date_timein . '</td>';
                $data .= '<td>' . $otr->date_timeout . '</td>';
                $data .= '<td>' . $otr->total_hrs . '</td>';

                //check status
                if($otr->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($otr->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($otr->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($otr->status != "CANCELLED" && $otr->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $otr->id .'" value="Cancel Alteration"></td>';
                }
                else{
                    $data .= '<td style="width:auto";></td>';
                }
                //end button cancel

                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
            </table>';
        }
        echo $data;
    }

    public function cancelOvertime(Request $request)
    {
        $update_query = OvertimeRecords::find($request->id);
        $update_query->status = 'CANCELLED';
        $update_query->lu_by = auth()->user()->name;
        $update_query->save();
    }
}
