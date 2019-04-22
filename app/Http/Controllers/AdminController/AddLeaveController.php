<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveRecords;
use App\Models\SubLeaveRecords;
use DB;
use DateTime;

class AddLeaveController extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.addleave.addleave');
    }

    public function getLeaveType()
    {
        $data = "";

        $GET_LEAVE_TEMP_QUERY = "SELECT id, leave_name FROM leave_template WHERE deleted = '0'";
        $GET_LEAVE_TEMP = DB::connection('mysql3')->select($GET_LEAVE_TEMP_QUERY);

        $data = '<option value= "">Select Leave</option>';

        if(count($GET_LEAVE_TEMP) > 0){
            foreach($GET_LEAVE_TEMP as $lt){

                $data .= '<option value='. $lt->id .'>' . $lt->leave_name . '</option>';
            }
        }
        echo $data;
    }

    public function getDays(Request $request)
    {

        $data = "";
        
        $GET_LEAVE_TEMP_QUERY = "SELECT id, leave_name, days_leave FROM leave_template WHERE id = '" . $request->id . "' AND deleted = '0' ";
        $GET_LEAVE_TEMP = DB::connection('mysql3')->select($GET_LEAVE_TEMP_QUERY);

        $data .= '<option value="">Select Days</option>';

        if(count($GET_LEAVE_TEMP) > 0){

            $data = '<option value=".5">0.5 Day (AM)</option>
                    <option value=".5">0.5 Day (PM)</option>';

            for($i = 1; $i <= $GET_LEAVE_TEMP[0]->days_leave; $i++){
                $data .= '<option value="' . $i . '">' . $i . '</option>';
            }
        }
        echo $data;
    }

    public function checkLeave(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        //date to alter
        $sched_date = date("Y-m-d", strtotime($request->input('schedDate')));

        if($request->input('cmbLeaveType') == ""){

            $message = "Please select Leave Type!";
            $error[] = $message;
        }
        else if($request->input('schedDate') == ""){

            $message = "Schedule Date is required!";
            $error[] = $message;
        }
        else if($request->input('cmbLeaveDays') == ""){

            $message = "Please select Leave Days!";
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

    public function addLeave(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();
        $try = "";

        //date now
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

        //date of leave
        $sched_date = date("Y-m-d", strtotime($request->input('schedDate')));

        //leave days
        $leave_days = $request->input('cmbLeaveDays');

        //leave types
        $leave_type = $request->input('cmbLeaveType');


        for($i = 1; $i <= $request->input('tblCount'); $i++){
            if($request->input('empList' . $i)){

                if($request->input('empList' . $i) == "on"){

                    $query = "SELECT start_date, company_id FROM leave_records WHERE start_date = '" . $sched_date . "' AND company_id = '" . $request->input('empListVal' . $i) . "'";
                    $leave_result = DB::connection('mysql')->select($query);

                    if(!empty($leave_result)){

                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Leave already exist";
                        $error[] = $message;
                    }   
                    else{
    
                        $query = "SELECT company_id, leave_id, leave_count FROM employee_leave WHERE company_id = '" . $request->input('empListVal' . $i) ."' AND leave_id = '". $leave_type ."' AND leave_count >= '" . $leave_days . "' AND deleted = '0'";
                        $check_leave = DB::connection('mysql3')->select($query);

                        if(!empty($check_leave)){

                            if($leave_days == ".5"){

                                $end_date = date("Y-m-d", strtotime("+ 4 hour", strtotime($sched_date)));
                            }
                            else{
                                
                                $dates = array();
                                $counter = 0;
                                $start_date = $sched_date;

                                while($counter != $leave_days){

                                    $query = "SELECT sched_date FROM sub_leave_records WHERE company_id = '" . $request->input('empListVal' . $i) . "' AND sched_date = '" . $start_date . "' AND deleted = '0'";
                                    $check_dates = DB::connection('mysql')->select($query);
                                    if(!empty($check_dates)){

                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Leave already exist";
                                        $error[] = $message;
                                    }

                                    $query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                                    b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                                    b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                                    WHERE a.deleted = '0' AND '" . $start_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '" . $request->input('empListVal' . $i) . "'";

                                    $sched_req = DB::connection('mysql3')->select($query);
                                    if(!empty($sched_req)){

                                        //reg
                                        if($sched_req[0]->type == "Regular Shift"){
                                            
                                            $day = date("N", strtotime($start_date)); //converts the date into day
                                            
                                            //monday
                                            if($day == "1"){

                                                if($sched_req[0]->mon == "1" || $sched_req[0]->mon == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }   
                                            }
                                            //tuesday
                                            else if($day == "2"){

                                                if($sched_req[0]->tue == "1" || $sched_req[0]->tue == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //wednesday
                                            else if($day == "3"){
                                                
                                                if($sched_req[0]->wed == "1" || $sched_req[0]->wed == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //thursday
                                            else if($day == "4"){
                                                
                                                if($sched_req[0]->thu == "1" || $sched_req[0]->thu == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //friday
                                            else if($day == "5"){

                                                if($sched_req[0]->fri == "1" || $sched_req[0]->fri == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //saturday
                                            else if($day == "6"){
                                                
                                                if($sched_req[0]->sat == "1" || $sched_req[0]->sat == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //sunday
                                            else if($day == "7"){
                                                
                                                if($sched_req[0]->sun == "1" || $sched_req[0]->sun == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                        }

                                        //irreg
                                        else if($sched_req[0]->type == "Irregular Shift"){

                                            $day = date("N", strtotime($start_date)); //converts the date into day
                                            
                                            //monday
                                            if($day == "1"){

                                                if($sched_req[0]->mon == "1" || $sched_req[0]->mon == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }   
                                            }
                                            //tuesday
                                            else if($day == "2"){

                                                if($sched_req[0]->tue == "1" || $sched_req[0]->tue == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //wednesday
                                            else if($day == "3"){
                                                
                                                if($sched_req[0]->wed == "1" || $sched_req[0]->wed == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //thursday
                                            else if($day == "4"){
                                                
                                                if($sched_req[0]->thu == "1" || $sched_req[0]->thu == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //friday
                                            else if($day == "5"){

                                                if($sched_req[0]->fri == "1" || $sched_req[0]->fri == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //saturday
                                            else if($day == "6"){
                                                
                                                if($sched_req[0]->sat == "1" || $sched_req[0]->sat == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //sunday
                                            else if($day == "7"){
                                                
                                                if($sched_req[0]->sun == "1" || $sched_req[0]->sun == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                        }
                                        
                                        //flexi
                                        else if($sched_req[0]->type == "Flexi Shift"){

                                            $day = date("N", strtotime($start_date)); //converts the date into day
                                            
                                            //monday
                                            if($day == "1"){

                                                if($sched_req[0]->mon == "1" || $sched_req[0]->mon == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }   
                                            }
                                            //tuesday
                                            else if($day == "2"){

                                                if($sched_req[0]->tue == "1" || $sched_req[0]->tue == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //wednesday
                                            else if($day == "3"){
                                                
                                                if($sched_req[0]->wed == "1" || $sched_req[0]->wed == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //thursday
                                            else if($day == "4"){
                                                
                                                if($sched_req[0]->thu == "1" || $sched_req[0]->thu == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //friday
                                            else if($day == "5"){

                                                if($sched_req[0]->fri == "1" || $sched_req[0]->fri == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //saturday
                                            else if($day == "6"){
                                                
                                                if($sched_req[0]->sat == "1" || $sched_req[0]->sat == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                            //sunday
                                            else if($day == "7"){
                                                
                                                if($sched_req[0]->sun == "1" || $sched_req[0]->sun == "2"){

                                                    $counter++;
                                                    $dates[] = $start_date;
                                                }  
                                            }
                                        }
                                        
                                        //free
                                        else if($sched_req[0]->type == "Free Shift"){
                                        }
                                    }
                                    else{

                                        $query = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, 
                                        b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, 
                                        b.fri_out, b.fri, b.sat_in, b.sat_out, b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule 
                                        AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind WHERE a.deleted = '0' AND a.company_id = '" . $request->input('empListVal' . $i) . "'";
                                        $emp_sched = DB::connection('mysql3')->select($query);

                                        if(!empty($emp_sched)){

                                            //reg
                                            if($emp_sched[0]->type == "Regular Shift"){

                                                $day = date("N", strtotime($start_date)); //converts the date into day
                                                
                                                //monday
                                                if($day == "1"){
                                                    
                                                    if($emp_sched[0]->mon == "1" || $emp_sched[0]->mon == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }   
                                                }
                                                //tuesday
                                                if($day == "2"){

                                                    if($emp_sched[0]->tue == "1" || $emp_sched[0]->tue == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                     
                                                }
                                                //wednesday
                                                else if($day == "3"){

                                                    if($emp_sched[0]->wed == "1" || $emp_sched[0]->wed == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                      
                                                }
                                                //thursday
                                                else if($day == "4"){

                                                    if($emp_sched[0]->thu == "1" || $emp_sched[0]->thu == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                    
                                                }
                                                //friday
                                                else if($day == "5"){

                                                    if($emp_sched[0]->fri == "1" || $emp_sched[0]->fri == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //satruday
                                                else if($day == "6"){

                                                    if($emp_sched[0]->sat == "1" || $emp_sched[0]->sat == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //sunday
                                                else if($day == "7"){

                                                    if($emp_sched[0]->sun == "1" || $emp_sched[0]->sun == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                            }
                                            //irreg
                                            else if($emp_sched[0]->type == "Irregular Shift"){

                                                $day = date("N", strtotime($start_date)); //converts the date into day
                                                
                                                //monday
                                                if($day == "1"){
                                                    
                                                    if($emp_sched[0]->mon == "1" || $emp_sched[0]->mon == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }   
                                                }
                                                //tuesday
                                                if($day == "2"){

                                                    if($emp_sched[0]->tue == "1" || $emp_sched[0]->tue == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                     
                                                }
                                                //wednesday
                                                else if($day == "3"){

                                                    if($emp_sched[0]->wed == "1" || $emp_sched[0]->wed == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                      
                                                }
                                                //thursday
                                                else if($day == "4"){

                                                    if($emp_sched[0]->thu == "1" || $emp_sched[0]->thu == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                    
                                                }
                                                //friday
                                                else if($day == "5"){

                                                    if($emp_sched[0]->fri == "1" || $emp_sched[0]->fri == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //satruday
                                                else if($day == "6"){

                                                    if($emp_sched[0]->sat == "1" || $emp_sched[0]->sat == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //sunday
                                                else if($day == "7"){

                                                    if($emp_sched[0]->sun == "1" || $emp_sched[0]->sun == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                            }
                                            //flexi
                                            else if($emp_sched[0]->type == "Flexi Shift"){

                                                $day = date("N", strtotime($start_date)); //converts the date into day
                                                
                                                //monday
                                                if($day == "1"){
                                                    
                                                    if($emp_sched[0]->mon == "1" || $emp_sched[0]->mon == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }   
                                                }
                                                //tuesday
                                                if($day == "2"){

                                                    if($emp_sched[0]->tue == "1" || $emp_sched[0]->tue == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                     
                                                }
                                                //wednesday
                                                else if($day == "3"){

                                                    if($emp_sched[0]->wed == "1" || $emp_sched[0]->wed == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }                                      
                                                }
                                                //thursday
                                                else if($day == "4"){

                                                    if($emp_sched[0]->thu == "1" || $emp_sched[0]->thu == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                    
                                                }
                                                //friday
                                                else if($day == "5"){

                                                    if($emp_sched[0]->fri == "1" || $emp_sched[0]->fri == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //satruday
                                                else if($day == "6"){

                                                    if($emp_sched[0]->sat == "1" || $emp_sched[0]->sat == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                                //sunday
                                                else if($day == "7"){

                                                    if($emp_sched[0]->sun == "1" || $emp_sched[0]->sun == "2"){

                                                        $counter++;
                                                        $dates[] = $start_date;
                                                    }
                                                }
                                            }
                                            //free
                                            else if($emp_sched[0]->type == "Free Shift"){

                                                
                                            }
                                        }
                                    }

                                    $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
                                    $end_date = date("Y-m-d", strtotime("-1 days", strtotime($start_date)));
                                }
                            }

                            $insert_query = new LeaveRecords;
                            $insert_query->company_id = $request->input('empListVal' . $i);
                            $insert_query->leave_id = $leave_type;
                            $insert_query->date_applied = $date_now;
                            $insert_query->reason = $request->input('txtReason');
                            $insert_query->duration = $leave_days;
                            $insert_query->start_date = $sched_date;
                            $insert_query->end_date = $end_date;
                            $insert_query->status = "APPROVED";
                            $insert_query->approved_1 = 1;
                            $insert_query->approved_2 = 1;
                            $insert_query->approved_1_id = auth()->user()->company_id;
                            $insert_query->approved_2_id = auth()->user()->company_id;
                            $insert_query->lu_by = auth()->user()->name;
                            $insert_query->save();

                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Leave successfully apply!";
                            $success[] = $message;

                            $leave_id = $insert_query->id;

                            $_start_date = date('Y-m-d', strtotime($request->input('schedDate')));
                            $_end_date = date('Y-m-d', strtotime($end_date));

                            if($request->input('cmbLeaveDays') == ".5"){

                                $insert_query = new SubLeaveRecords;
                                $insert_query->company_id = $request->input('empListVal' . $i);
                                $insert_query->leave_record_id = $leave_id;
                                $insert_query->sched_date = $sched_date;
                                $insert_query->lu_by = auth()->user()->name;
                                $insert_query->save();
                            }
                            else{

                                foreach($dates as $key => $s_dates){

                                    $insert_query = new SubLeaveRecords;
                                    $insert_query->company_id = $request->input('empListVal' . $i);
                                    $insert_query->leave_record_id = $leave_id;
                                    $insert_query->sched_date = $s_dates;
                                    $insert_query->lu_by = auth()->user()->name;
                                    $insert_query->save();
                                }
                            }

                            $query = "UPDATE employee_leave SET leave_count = (leave_count - '" . $leave_days . "'), 
                            balance_leave = leave_count , used_leave = used_leave + '" . $leave_days . "' WHERE company_id = '" . $request->input('empListVal' . $i) . "'
                            AND leave_id = '" . $leave_type . "'";
                            $leave_count = DB::connection('mysql3')->select($query);
                        }
                        else{

                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Not enough leave/No such leave!";
                            $error[] = $message;
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

    public function searchLeaveRecord(Request $request)
    {
        $data = "";

        $SEARCH_LEAVE_RECORDS_QUERY = "SELECT id, date_applied, company_id, emp_name, leave_name, duration, start_date, end_date, status, approved_1, approved_2, payroll_register_number FROM view_leave_records";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_LEAVE_RECORDS_QUERY .= " ORDER BY date_applied, id DESC";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND deleted = '0' ORDER BY date_applied, id DESC";
                }
                else{
                    $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                    }
                    else{
                        $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_LEAVE_RECORDS_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC"; 
                        }
                    }
                }
            }
        }

        $SEARCH_LEAVE_RECORDS = DB::connection('mysql')->select($SEARCH_LEAVE_RECORDS_QUERY);

        $data = '<table id="tableLeaveRecord" name="tableLeaveRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="10" class="text-center">LEAVED RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Type of Leave</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($SEARCH_LEAVE_RECORDS) > 0){          
            foreach($SEARCH_LEAVE_RECORDS as $lr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $lr->company_id . '</td>';
                $data .= '<td>' . $lr->emp_name . '</td>';
                $data .= '<td>' . $lr->date_applied . '</td>';             
                $data .= '<td>' . $lr->leave_name . '</td>';
                $data .= '<td>' . $lr->duration . '</td>';
                $data .= '<td>' . $lr->start_date . '</td>';
                $data .= '<td>' . $lr->end_date . '</td>';

                //check status
                if($lr->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($lr->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($lr->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($lr->status != "CANCELLED" && $lr->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $lr->id .'" value="Cancel Alteration"></td>';
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

    public function getLeaveRecords()
    {
        $data = "";

        $GET_LEAVE_RECORDS_QUERY = "SELECT id, date_applied, company_id, emp_name, leave_record_id, leave_name, duration, start_date, end_date, status, approved_1, approved_2, payroll_register_number FROM view_leave_records WHERE deleted = '0' ORDER BY date_applied, id DESC";
        $GET_LEAVE_RECORDS = DB::connection('mysql')->select($GET_LEAVE_RECORDS_QUERY);

        $data = '<table id="tableLeaveRecord" name="tableLeaveRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="9" class="text-center">LEAVED RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Type of Leave</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($GET_LEAVE_RECORDS) > 0){          
            foreach($GET_LEAVE_RECORDS as $lr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $lr->company_id . '</td>';
                $data .= '<td>' . $lr->emp_name . '</td>';
                $data .= '<td>' . $lr->date_applied . '</td>';             
                $data .= '<td>' . $lr->leave_name . '</td>';
                $data .= '<td>' . $lr->duration . '</td>';
                $data .= '<td>' . $lr->start_date . '</td>';
                $data .= '<td>' . $lr->end_date . '</td>';

                //check status
                if($lr->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($lr->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($lr->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($lr->status != "CANCELLED" && $lr->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $lr->id .'" value="Cancel Alteration"></td>';
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

    public function cancelLeave(Request $request)
    {
        $update_query = LeaveRecords::find($request->id);
        $update_query->status = 'CANCELLED';
        $update_query->lu_by = auth()->user()->name;
        $update_query->save();
    }
}
