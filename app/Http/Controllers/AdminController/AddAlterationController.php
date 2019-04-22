<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlterationRecords;
use DB;
use DateTime;

class AddAlterationController extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.addalteration.addalteration');
    }
    
    public function getEmployeeInfo()
    {
        $data = "";

        $GET_EMPLOYEE_QUERY = "SELECT * FROM view_employee_information ORDER BY fullname";
        $GET_EMPLOYEE = DB::connection('mysql')->select($GET_EMPLOYEE_QUERY);

        $data .= '<table id="tableEmployeeList" name="tableEmployeeList" class="table table-hover" cellspacing="0" style="width:100%" >
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="8" class="text-center">EMPLOYEE LIST</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Team</th>
                            <th>Employment Status</th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($GET_EMPLOYEE) > 0){
            foreach($GET_EMPLOYEE as $ei){
                $data .= '<tr>';

                $data .= '<td> 
                            <input id="empList' . $i . '" name="empList' . $i . '" type="checkbox"></td>
                            <input id="empListVal' . $i . '" name="empListVal' . $i . '" type="hidden" value="' . $ei->company_id . '"></td>';
                $data .= '<td>' . $ei->company_id . '</td>';
                $data .= '<td>
                            <input type="text" style="display:none" value="'.$ei->fullname.'" id="fname'.$i.'" name="fname'.$i.'">' . $ei->fullname . '</td>';
                $data .= '<td>' . $ei->company_name . '</td>';
                $data .= '<td>' . $ei->department . '</td>';
                $data .= '<td>' . $ei->position . '</td>';
                $data .= '<td>' . $ei->team . '</td>';
                $data .= '<td>' . $ei->employment_status . '</td>';
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>
                    <input id="tblCount" name="tblCount" type="hidden" value="' . $i . '">';
        }
        echo $data;
    }

    public function getTeam(Request $request)
    {
        $data="";

        $GET_TEAM_QUERY = "SELECT ind, team_name FROM team WHERE active = 'yes' AND department_ind ='" . $request->deptind . "' ORDER BY team_name";
        $GET_TEAM = DB::connection('mysql3')->select($GET_TEAM_QUERY);

        $data .= '<option value="">Select Team</option>';
        if(count($GET_TEAM) > 0){
            foreach($GET_TEAM as $t){   
                
                $data .= '<option value="'. $t->team_name . '">' . $t->team_name .'</option>';
            }
        }
        echo $data;
    }

    public function searchEmployee(Request $request)
    {
        $data = "";

        $SEARCH_EMPLOYEE_QUERY = "SELECT * FROM view_employee_information";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_EMPLOYEE_QUERY .= " ORDER BY fullname";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' ORDER BY fullname";
                }
                else{
                    $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' ORDER BY fullname";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                    else{
                        $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMPLOYEE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname"; 
                        }
                    }
                }
            }
        }
        $SEARCH_EMPLOYEE = DB::connection('mysql')->select($SEARCH_EMPLOYEE_QUERY);

        $data .= '<table id="tableEmployeeList" name="tableEmployeeList" class="table table-hover" cellspacing="0" style="width:100%" >
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="8" class="text-center">EMPLOYEE LIST</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Team</th>
                            <th>Employment Status</th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($SEARCH_EMPLOYEE) > 0){
            foreach($SEARCH_EMPLOYEE as $el){
                $data .= '<tr>';
                $data .= '<td> 
                            <input id="empList' . $i . '" name="empList' . $i . '" type="checkbox"></td>
                            <input id="empListVal' . $i . '" name="empListVal' . $i . '" type="hidden" value="' . $el->company_id . '"></td>';
                $data .= '<td>' . $el->company_id . '</td>';
                $data .= '<td><input type="text" style="display:none" value="' . $el->fullname .'" id="fname' . $i . '" name="fname' . $i .'">' . $el->fullname . '</td>';
                $data .= '<td>' . $el->company_name . '</td>';
                $data .= '<td>' . $el->department . '</td>';
                $data .= '<td>' . $el->position . '</td>';
                $data .= '<td>' . $el->team . '</td>';
                $data .= '<td>' . $el->employment_status . '</td>';
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>
                    <input id="tblCount" name="tblCount" type="hidden" value="' . $i . '">';
        }
        echo $data;     
    }

    public function checkAlter(Request $request)
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
        else if($date_now <= $sched_date){

            $message = "Schdule Date must be less than in Date Today!";
            $error[] = $message;
        }
        else if($datetimeout <= $datetimein){

            $message = "Date time out must greater than Date time in!";
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

    public function addAlter(Request $request)
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

        for($i = 1; $i <= $request->input('tblCount'); $i++){

            $alter = "false"; 
            $hour = 0.0;
            $total_hours = 0.0;
            $total_undertime = 0.0;
            $total_flexihrs = 0.0;
            $total_late = 0.0;

            if($request->input('empList' . $i)){

                if($request->input('empList' . $i) == "on"){

                    // check if the date want to alter is already exist
                    $CHECK_ALTER_QUERY = "SELECT sched_date, company_id FROM alteration_records WHERE sched_date = '" . $sched_date . "' AND company_id = '" . $request->input('empListVal' . $i) . "' AND status <> 'CANCELLED'";
                    $CHECK_ALTER_EXIST = DB::connection('mysql')->select($CHECK_ALTER_QUERY);

                    if(!empty($CHECK_ALTER_EXIST)){

                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Altered already exist";
                        $error[] = $message;
                    }
                    else{

                        //employee schedule request
                        $SELECT_SCHEDULE_REQUEST_QUERY = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                        b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                        b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                        WHERE a.deleted = '0' AND '" . $sched_date . "' BETWEEN a.start_date AND a.end_date AND a.company_id = '" . $request->input('empListVal' . $i) . "'";
                        $SELECT_SCHEDULE_REQUEST = DB::connection('mysql3')->select($SELECT_SCHEDULE_REQUEST_QUERY);

                        if(!empty($SELECT_SCHEDULE_REQUEST)){

                            //regular employee schedule
                            if($SELECT_SCHEDULE[0]->type == "Regular Shift"){
                                    
                                $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                //monday
                                if($day == "1"){
                                    if($SELECT_SCHEDULE[0]->mon == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day monday

                                //tuesday
                                if($day == "2"){
                                    if($SELECT_SCHEDULE[0]->tue == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day tuesday

                                //wednesday
                                if($day == "3"){
                                    if($SELECT_SCHEDULE[0]->wed == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day wednesday

                                //thursday
                                if($day == "4"){
                                    if($SELECT_SCHEDULE[0]->thu == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day thursday

                                //friday
                                if($day == "5"){
                                    if($SELECT_SCHEDULE[0]->fri == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day friday

                                //saturday
                                if($day == "6"){
                                    if($SELECT_SCHEDULE[0]->sat == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day saturday

                                //sunday
                                if($day == "7"){
                                    if($SELECT_SCHEDULE[0]->sun == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //reg in time
                                        $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                        //time in time
                                        $_timein = new DateTime($timein);
                                        //date timeout shift
                                        $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //if undertime
                                        if($_datetimeout < $reg_datetimeout){

                                            $interval = $reg_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $reg_timein){

                                            $interval = $reg_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $reg_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day sunday
                            }
                            //end regular employee schedule

                            //irregular shift employee schedule
                            else if($SELECT_SCHEDULE[0]->type == "Irregular Shift"){

                                $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                 //monday
                                 if($day == "1"){
                                    if($SELECT_SCHEDULE[0]->mon == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $mon_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->mon_out);
                                        //time in
                                        $_timein = new DateTime($timein);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in shift
                                        $mon_timein = new DateTime($SELECT_SCHEDULE[0]->mon_in);

                                        //if undertime
                                        if($_datetimeout < $mon_datetimeout){

                                            $interval = $mon_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $mon_timein){

                                            $interval = $mon_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $mon_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->mon_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day monday

                                //tuesday
                                if($day == "2"){
                                    if($SELECT_SCHEDULE[0]->tue == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $tue_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->tue_out);
                                        //time in
                                        $_timein = new DateTime($timein);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in shift
                                        $tue_timein = new DateTime($SELECT_SCHEDULE[0]->tue_in);

                                        //if undertime
                                        if($_datetimeout < $tue_datetimeout){

                                            $interval = $tue_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $tue_timein){

                                            $interval = $tue_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $tue_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->tue_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day tuesday

                                //wednesday
                                if($day == "3"){
                                    if($SELECT_SCHEDULE[0]->wed == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $wed_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->wed_out);
                                        //time in
                                        $_timein = new DateTime($timein);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in shift
                                        $wed_timein = new DateTime($SELECT_SCHEDULE[0]->wed_in);

                                        //if undertime
                                        if($_datetimeout < $wed_datetimeout){

                                            $interval = $wed_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $wed_timein){

                                            $interval = $wed_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $wed_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->wed_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day wednesday

                                //thursday
                                if($day == "4"){
                                    if($SELECT_SCHEDULE[0]->thu == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $thu_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->thu_out);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in
                                        $_timein = new DateTime($timein);

                                        //time in shift
                                        $thu_timein = new DateTime($SELECT_SCHEDULE[0]->thu_in);

                                        //if undertime
                                        if($_datetimeout < $thu_datetimeout){

                                            $interval = $thu_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $thu_timein){

                                            $interval = $thu_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $thu_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->thu_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day thursday

                                //friday
                                if($day == "5"){
                                    if($SELECT_SCHEDULE[0]->fri == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $fri_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->fri_out);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in
                                        $_timein = new DateTime($timein);

                                        //time in shift
                                        $fri_timein = new DateTime($SELECT_SCHEDULE[0]->fri_in);

                                        //if undertime
                                        if($_datetimeout < $fri_datetimeout){

                                            $interval = $fri_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $fri_timein){

                                            $interval = $fri_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $fri_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->fri_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day friday

                                //saturday
                                if($day == "6"){
                                    if($SELECT_SCHEDULE[0]->sat == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $sat_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sat_out);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in
                                        $_timein = new DateTime($timein);

                                        //time in shift
                                        $sat_timein = new DateTime($SELECT_SCHEDULE[0]->sat_in);

                                        //if undertime
                                        if($_datetimeout < $sat_datetimeout){

                                            $interval = $sat_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $sat_timein){

                                            $interval = $sat_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $sat_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sat_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day saturday

                                //sunday
                                if($day == "7"){
                                    if($SELECT_SCHEDULE[0]->sun == "1"){

                                        $alter = "true";
                                        //convert date time out to date only
                                        $date_out = date('Y-m-d', strtotime($datetimeout));
                                        //date timeout shift
                                        $sun_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sun_out);

                                        //date time out
                                        $_datetimeout = new DateTime($datetimeout);

                                        //time in
                                        $_timein = new DateTime($timein);

                                        //time in shift
                                        $sun_timein = new DateTime($SELECT_SCHEDULE[0]->sun_in);

                                        //if undertime
                                        if($_datetimeout < $sun_datetimeout){

                                            $interval = $sun_datetimeout->diff($_datetimeout);
                                            $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_undertime = 0.0;
                                        }
                                        //end undertime

                                        //if late
                                        if($_timein > $sun_timein){

                                            $interval = $sun_timein->diff($_timein);
                                            $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $total_late = 0.0;
                                        }
                                        //end late

                                        //compute total hours
                                        if($_timein < $sun_timein){

                                            $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sun_in);
                                            $d2 = new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                        }
                                        else{

                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);
                                            $interval = $d1->diff($d2);
                                            $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                        }
                                        //end total hours
                                    }
                                    else{

                                        $alter = "false"; 
                                        $hour = 0.0;
                                        $total_hours = 0.0;
                                        $total_undertime = 0.0;
                                        $total_flexihrs = 0.0;
                                        $total_late = 0.0;
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;
                                    }
                                }
                                //end day sunday
                            }
                            //end irregular shift employee schedule

                            //flexi shift employee schedule
                            else if($SELECT_SCHEDULE[0]->type == "Flexi Shift"){

                                $total_flexihrs = $SELECT_SCHEDULE_QUERY[0]->flexihrs;

                                $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                //monday
                                if($day == "1"){

                                    if($SELECT_SCHEDULE_QUERY[0]->mon == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day monday

                                //tuesday
                                if($day == "2"){

                                    if($SELECT_SCHEDULE_QUERY[0]->tue == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day tuesday

                                //wednesday
                                if($day == "3"){

                                    if($SELECT_SCHEDULE_QUERY[0]->wed == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day wednesday

                                //thursday
                                if($day == "4"){

                                    if($SELECT_SCHEDULE_QUERY[0]->thu == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day thursday

                                //friday
                                if($day == "5"){

                                    if($SELECT_SCHEDULE_QUERY[0]->fri == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day friday

                                //saturday
                                if($day == "6"){

                                    if($SELECT_SCHEDULE_QUERY[0]->sat == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day saturday

                                //sunday
                                if($day == "7"){

                                    if($SELECT_SCHEDULE_QUERY[0]->sun == "1"){

                                        $alter = "true";
                                        
                                        $d1= new DateTime($datetimein); 
                                        $d2= new DateTime($datetimeout);

                                        $interval = $d1->diff($d2);
                                        $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                        if($total_hours <= $total_flexihrs){

                                            $total_undertime = $total_flexihrs - $total_hours;
                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                        }
                                        else{

                                            $hour = $total_hours;
                                            $total_late = 0.0;
                                            $total_undertime = 0.0;
                                        }
                                    }
                                    else
                                    {
                                        $alter = "false"; 
                                        $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                        $error[] = $message;   
                                    }
                                }
                                //end day sunday
                            }
                            //end flexi shift employee schedule
                        }
                        else{
                            
                            //employee schedule
                            $SELECT_SCHEDULE_QUERY = "SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                            WHERE a.deleted = '0' AND a.company_id = '" . $request->input('empListVal' . $i) . "'";
                            $SELECT_SCHEDULE = DB::connection('mysql3')->select($SELECT_SCHEDULE_QUERY);

                            if(!empty($SELECT_SCHEDULE)){

                                //regular employee schedule
                                if($SELECT_SCHEDULE[0]->type == "Regular Shift"){
                                    
                                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                    //monday
                                    if($day == "1"){
                                        if($SELECT_SCHEDULE[0]->mon == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day monday

                                    //tuesday
                                    if($day == "2"){
                                        if($SELECT_SCHEDULE[0]->tue == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day tuesday

                                    //wednesday
                                    if($day == "3"){
                                        if($SELECT_SCHEDULE[0]->wed == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day wednesday

                                    //thursday
                                    if($day == "4"){
                                        if($SELECT_SCHEDULE[0]->thu == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day thursday

                                    //friday
                                    if($day == "5"){
                                        if($SELECT_SCHEDULE[0]->fri == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day friday

                                    //saturday
                                    if($day == "6"){
                                        if($SELECT_SCHEDULE[0]->sat == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day saturday

                                    //sunday
                                    if($day == "7"){
                                        if($SELECT_SCHEDULE[0]->sun == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //reg in time
                                            $reg_timein = new DateTime($SELECT_SCHEDULE[0]->reg_in);
                                            //time in time
                                            $_timein = new DateTime($timein);
                                            //date timeout shift
                                            $reg_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_out);
                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //if undertime
                                            if($_datetimeout < $reg_datetimeout){

                                                $interval = $reg_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $reg_timein){

                                                $interval = $reg_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $reg_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->reg_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day sunday
                                }
                                //end regular employee schedule

                                //irregular shift employee schedule
                                else if($SELECT_SCHEDULE[0]->type == "Irregular Shift"){

                                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                     //monday
                                     if($day == "1"){
                                        if($SELECT_SCHEDULE[0]->mon == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $mon_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->mon_out);
                                            //time in
                                            $_timein = new DateTime($timein);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in shift
                                            $mon_timein = new DateTime($SELECT_SCHEDULE[0]->mon_in);

                                            //if undertime
                                            if($_datetimeout < $mon_datetimeout){

                                                $interval = $mon_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $mon_timein){

                                                $interval = $mon_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $mon_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->mon_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day monday

                                    //tuesday
                                    if($day == "2"){
                                        if($SELECT_SCHEDULE[0]->tue == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $tue_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->tue_out);
                                            //time in
                                            $_timein = new DateTime($timein);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in shift
                                            $tue_timein = new DateTime($SELECT_SCHEDULE[0]->tue_in);

                                            //if undertime
                                            if($_datetimeout < $tue_datetimeout){

                                                $interval = $tue_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $tue_timein){

                                                $interval = $tue_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $tue_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->tue_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day tuesday

                                    //wednesday
                                    if($day == "3"){
                                        if($SELECT_SCHEDULE[0]->wed == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $wed_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->wed_out);
                                            //time in
                                            $_timein = new DateTime($timein);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in shift
                                            $wed_timein = new DateTime($SELECT_SCHEDULE[0]->wed_in);

                                            //if undertime
                                            if($_datetimeout < $wed_datetimeout){

                                                $interval = $wed_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $wed_timein){

                                                $interval = $wed_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $wed_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->wed_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day wednesday

                                    //thursday
                                    if($day == "4"){
                                        if($SELECT_SCHEDULE[0]->thu == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $thu_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->thu_out);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in
                                            $_timein = new DateTime($timein);

                                            //time in shift
                                            $thu_timein = new DateTime($SELECT_SCHEDULE[0]->thu_in);

                                            //if undertime
                                            if($_datetimeout < $thu_datetimeout){

                                                $interval = $thu_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $thu_timein){

                                                $interval = $thu_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $thu_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->thu_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day thursday

                                    //friday
                                    if($day == "5"){
                                        if($SELECT_SCHEDULE[0]->fri == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $fri_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->fri_out);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in
                                            $_timein = new DateTime($timein);

                                            //time in shift
                                            $fri_timein = new DateTime($SELECT_SCHEDULE[0]->fri_in);

                                            //if undertime
                                            if($_datetimeout < $fri_datetimeout){

                                                $interval = $fri_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $fri_timein){

                                                $interval = $fri_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $fri_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->fri_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day friday

                                    //saturday
                                    if($day == "6"){
                                        if($SELECT_SCHEDULE[0]->sat == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $sat_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sat_out);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in
                                            $_timein = new DateTime($timein);

                                            //time in shift
                                            $sat_timein = new DateTime($SELECT_SCHEDULE[0]->sat_in);

                                            //if undertime
                                            if($_datetimeout < $sat_datetimeout){

                                                $interval = $sat_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $sat_timein){

                                                $interval = $sat_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $sat_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sat_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day saturday

                                    //sunday
                                    if($day == "7"){
                                        if($SELECT_SCHEDULE[0]->sun == "1"){

                                            $alter = "true";
                                            //convert date time out to date only
                                            $date_out = date('Y-m-d', strtotime($datetimeout));
                                            //date timeout shift
                                            $sun_datetimeout = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sun_out);

                                            //date time out
                                            $_datetimeout = new DateTime($datetimeout);

                                            //time in
                                            $_timein = new DateTime($timein);

                                            //time in shift
                                            $sun_timein = new DateTime($SELECT_SCHEDULE[0]->sun_in);

                                            //if undertime
                                            if($_datetimeout < $sun_datetimeout){

                                                $interval = $sun_datetimeout->diff($_datetimeout);
                                                $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_undertime = 0.0;
                                            }
                                            //end undertime

                                            //if late
                                            if($_timein > $sun_timein){

                                                $interval = $sun_timein->diff($_timein);
                                                $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $total_late = 0.0;
                                            }
                                            //end late

                                            //compute total hours
                                            if($_timein < $sun_timein){

                                                $d1 = new DateTime($sched_date . " " . $SELECT_SCHEDULE[0]->sun_in);
                                                $d2 = new DateTime($datetimeout);

                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                            }
                                            else{

                                                $d1= new DateTime($datetimein); 
                                                $d2= new DateTime($datetimeout);
                                                $interval = $d1->diff($d2);
                                                $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                            }
                                            //end total hours
                                        }
                                        else{

                                            $alter = "false"; 
                                            $hour = 0.0;
                                            $total_hours = 0.0;
                                            $total_undertime = 0.0;
                                            $total_flexihrs = 0.0;
                                            $total_late = 0.0;
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;
                                        }
                                    }
                                    //end day sunday
                                }
                                //end irregular shift employee schedule

                                //flexi shift employee schedule
                                else if($SELECT_SCHEDULE[0]->type == "Flexi Shift"){

                                    $total_flexihrs = $SELECT_SCHEDULE_QUERY[0]->flexihrs;

                                    $day = date("N", strtotime($request->input('schedDate'))); //converts the date into day

                                    //monday
                                    if($day == "1"){

                                        if($SELECT_SCHEDULE_QUERY[0]->mon == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day monday

                                    //tuesday
                                    if($day == "2"){

                                        if($SELECT_SCHEDULE_QUERY[0]->tue == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day tuesday

                                    //wednesday
                                    if($day == "3"){

                                        if($SELECT_SCHEDULE_QUERY[0]->wed == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day wednesday

                                    //thursday
                                    if($day == "4"){

                                        if($SELECT_SCHEDULE_QUERY[0]->thu == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day thursday

                                    //friday
                                    if($day == "5"){

                                        if($SELECT_SCHEDULE_QUERY[0]->fri == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day friday

                                    //saturday
                                    if($day == "6"){

                                        if($SELECT_SCHEDULE_QUERY[0]->sat == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day saturday

                                    //sunday
                                    if($day == "7"){

                                        if($SELECT_SCHEDULE_QUERY[0]->sun == "1"){

                                            $alter = "true";
                                            
                                            $d1= new DateTime($datetimein); 
                                            $d2= new DateTime($datetimeout);

                                            $interval = $d1->diff($d2);
                                            $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                                            if($total_hours <= $total_flexihrs){

                                                $total_undertime = $total_flexihrs - $total_hours;
                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                            }
                                            else{

                                                $hour = $total_hours;
                                                $total_late = 0.0;
                                                $total_undertime = 0.0;
                                            }
                                        }
                                        else
                                        {
                                            $alter = "false"; 
                                            $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Apply it on Overtime";
                                            $error[] = $message;   
                                        }
                                    }
                                    //end day sunday
                                }
                                //end flexi shift employee schedule
                                
                                if($alter == "true")
                                {
                                    $insert_query = new AlterationRecords;
                                    $insert_query->company_id = $request->input('empListVal' . $i);
                                    $insert_query->date_applied = $date_now;
                                    $insert_query->sched_date = $sched_date;
                                    $insert_query->new_time_in = $datetimein;
                                    $insert_query->new_time_out = $datetimeout;
                                    $insert_query->total_hrs = $hour;
                                    $insert_query->undertime = $total_undertime;
                                    $insert_query->late = $total_late;
                                    $insert_query->reason = $request->input('txtReason');
                                    $insert_query->status = "APPROVED";
                                    $insert_query->approved_1 = 1;
                                    $insert_query->approved_2 = 1;
                                    $insert_query->approved_1_id = auth()->user()->company_id;
                                    $insert_query->approved_2_id = auth()->user()->company_id;
                                    $insert_query->lu_by = auth()->user()->name;
                                    $insert_query->save();
    
                                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Alteration successfully apply!";
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

    public function searchAlterationRecord(Request $request)
    {
        $data = "";

        $SEARCH_ALTER_RECORD_QUERY = "SELECT id, date_applied, company_id, emp_name, new_time_in, new_time_out, total_hrs, status, undertime, late, approved_1, approved_2, payroll_register_number FROM view_alteration_records";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_ALTER_RECORD_QUERY .= " ORDER BY date_applied, id DESC";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND deleted = '0' ORDER BY date_applied, id DESC";
                }
                else{
                    $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0'  ORDER BY date_applied, id DESC";
                    }
                    else{
                        $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC";
                        }
                        else{
                            $SEARCH_ALTER_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' AND deleted = '0' ORDER BY date_applied, id DESC"; 
                        }
                    }
                }
            }
        }

        $SEARCH_ALTER_RECORDS = DB::connection('mysql')->select($SEARCH_ALTER_RECORD_QUERY);

        $data = '<table id="tableAlterationRecord" name="tableAlterationRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="10" class="text-center">ALTERATION RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Applied Time In</th>
                            <th>Applied Time Out</th>
                            <th>Total Hours</th>
                            <th>Undertime</th>
                            <th>Late</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($SEARCH_ALTER_RECORDS) > 0){          
            foreach($SEARCH_ALTER_RECORDS as $ar){  
                
                $data .= '<tr>';
                $data .= '<td>' . $ar->company_id . '</td>';
                $data .= '<td>' . $ar->emp_name . '</td>';
                $data .= '<td>' . $ar->date_applied . '</td>';             
                $data .= '<td>' . $ar->new_time_in . '</td>';
                $data .= '<td>' . $ar->new_time_out . '</td>';
                $data .= '<td>' . $ar->total_hrs . '</td>';
                $data .= '<td>' . $ar->undertime . '</td>';
                $data .= '<td>' . $ar->late . '</td>';

                //check status
                if($ar->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($ar->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($ar->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($ar->status != "CANCELLED" && $ar->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $ar->id .'" value="Cancel Alteration"></td>';
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

    public function getAlterationRecord()
    {
        $data = "";

        $GET_ALTER_RECORD_QUERY = "SELECT id, date_applied, company_id, emp_name, new_time_in, new_time_out, total_hrs, approved_1, approved_2, status, undertime, late, payroll_register_number FROM view_alteration_records WHERE deleted = '0' ORDER BY date_applied, id DESC";
        $GET_ALTER_RECORD = DB::connection('mysql')->select($GET_ALTER_RECORD_QUERY);

        $data = '<table id="tableAlterationRecord" name="tableAlterationRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="10" class="text-center">ALTERATION RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Date Applied</th>
                            <th>Applied Time In</th>
                            <th>Applied Time Out</th>
                            <th>Total Hours</th>
                            <th>Undertime</th>
                            <th>Late</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($GET_ALTER_RECORD) > 0){          
            foreach($GET_ALTER_RECORD as $ar){  
                
                $data .= '<tr>';
                $data .= '<td>' . $ar->company_id . '</td>';
                $data .= '<td>' . $ar->emp_name . '</td>';
                $data .= '<td>' . $ar->date_applied . '</td>';             
                $data .= '<td>' . $ar->new_time_in . '</td>';
                $data .= '<td>' . $ar->new_time_out . '</td>';
                $data .= '<td>' . $ar->total_hrs . '</td>';
                $data .= '<td>' . $ar->undertime . '</td>';
                $data .= '<td>' . $ar->late . '</td>';
                
                //check status
                if($ar->status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>';
                }
                else if($ar->status == "PENDING"){
                    $data .= '<td style="color:#E87B15";><i class="icon-right fa fa-question-circle"></i><b>PENDING</b></td>';
                }
                else if($ar->status == "APPROVED"){
                    $data .= '<td style="color:#28a745";><i class="icon-right fa fa-check-circle"></i><b>APPROVED</b></td>';
                }
                //end check status

                //add button cancel
                if($ar->status != "CANCELLED" && $ar->payroll_register_number == ""){
                    $data .= '<td style="width:auto";><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $ar->id .'" value="Cancel Alteration"></td>';
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

    public function cancelAlteration(Request $request)
    {
        $update_query = AlterationRecords::find($request->id);
        $update_query->status = 'CANCELLED';
        $update_query->lu_by = auth()->user()->name;
        $update_query->save();
    }
}
