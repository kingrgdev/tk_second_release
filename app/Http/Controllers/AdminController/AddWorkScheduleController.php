<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ScheduleRequestRecords;
use App\Models\ScheduleTemplateRecords;
use DB;
use DateTime;

class AddWorkScheduleController extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.addworkschedule.addworkschedule');
    }

    public function getScheduleType()
    {
        $data = "";

        $GET_SCHEDULE_TYPE_QUERY = "SELECT ind, template, type FROM schedule_template WHERE deleted = '0'";
        $GET_SCHEDULE_TYPE = DB::connection('mysql3')->select($GET_SCHEDULE_TYPE_QUERY);

        $data .= '<option value="">Select Schedule</option>';
        if(count($GET_SCHEDULE_TYPE) > 0){
            foreach($GET_SCHEDULE_TYPE as $st){   
                
                $data .= '<option value="'. $st->ind . '">' . $st->template .'</option>';
            }
        }
        echo $data;
    }

    public function getSchedule(Request $request)
    {
        $schedule = array();
        $data = "";

        $GET_SCHEDULE_QUERY = "SELECT * FROM schedule_template WHERE ind = '" . $request->ind . "' AND deleted = '0'";
        $GET_SCHEDULE = DB::connection('mysql3')->select($GET_SCHEDULE_QUERY);

        if(!empty($GET_SCHEDULE)){

            $lunch_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->lunch_in));
            $lunch_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->lunch_out));
    
            $sched_type = $GET_SCHEDULE[0]->type;
    
            if($GET_SCHEDULE[0]->type == "Regular Shift"){
    
                $reg_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->reg_in));
                $reg_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->reg_out));
    
                //monday
                if($GET_SCHEDULE[0]->mon == "1" || $GET_SCHEDULE[0]->mon == "2"){
    
                    $mon = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $mon = "Rest Day";
                }
                //tuesday
                if($GET_SCHEDULE[0]->tue == "1" || $GET_SCHEDULE[0]->tue == "2"){
    
                    $tue = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $tue = "Rest Day";
                }
                //wednesday
                if($GET_SCHEDULE[0]->wed == "1" || $GET_SCHEDULE[0]->wed == "2"){
    
                    $wed = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $wed = "Rest Day";
                }
                //thursday
                if($GET_SCHEDULE[0]->thu == "1" || $GET_SCHEDULE[0]->thu == "2"){
    
                    $thu = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $thu = "Rest Day";
                }
                //friday
                if($GET_SCHEDULE[0]->fri == "1" || $GET_SCHEDULE[0]->fri == "2"){
    
                    $fri = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $fri = "Rest Day";
                }
                //saturday
                if($GET_SCHEDULE[0]->sat == "1" || $GET_SCHEDULE[0]->sat == "2"){
    
                    $sat = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $sat = "Rest Day";
                }
                //sunday
                if($GET_SCHEDULE[0]->sun == "1" || $GET_SCHEDULE[0]->sun == "2"){
    
                    $sun = $reg_in . " to " . $reg_out;
                }
                else{
    
                    $sun = "Rest Day";
                }
            }
            else if($GET_SCHEDULE[0]->type == "Irregular Shift"){
    
                //monday
                if($GET_SCHEDULE[0]->mon == "1" || $GET_SCHEDULE[0]->mon == "2"){
    
                    $mon_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_in));
                    $mon_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_out));
    
                    $mon = $mon_in . " to " . $mon_out;
                }
                else{
    
                    $mon = "Rest Day";
                }
                //tuesday
                if($GET_SCHEDULE[0]->tue == "1" || $GET_SCHEDULE[0]->tue == "2"){
    
                    $tue_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_in));
                    $tue_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_out));
    
                    $tue = $tue_in . " to " . $tue_out;
                }
                else{
    
                    $tue = "Rest Day";
                }
                //wednesday
                if($GET_SCHEDULE[0]->wed == "1" || $GET_SCHEDULE[0]->wed == "2"){
    
                    $wed_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_in));
                    $wed_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_out));
    
                    $wed = $wed_in . " to " . $wed_out;
                }
                else{
    
                    $wed = "Rest Day";
                }
                //thursday
                if($GET_SCHEDULE[0]->thu == "1" || $GET_SCHEDULE[0]->thu == "2"){
    
                    $thu_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_in));
                    $thu_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_out));
    
                    $thu = $thu_in . " to " . $thu_out;
                }
                else{
    
                    $thu = "Rest Day";
                }
                //friday
                if($GET_SCHEDULE[0]->fri == "1" || $GET_SCHEDULE[0]->fri == "2"){
    
                    $fri_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_in));
                    $fri_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_out));
    
                    $fri = $fri_in . " to " . $fri_out;
                }
                else{
    
                    $fri = "Rest Day";
                }
                //saturday
                if($GET_SCHEDULE[0]->sat == "1" || $GET_SCHEDULE[0]->sat == "2"){
    
                    $sat_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_in));
                    $sat_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_out));
    
                    $sat = $sat_in . " to " . $sat_out;
                }
                else{
    
                    $sat = "Rest Day";
                }
                //sunday
                if($GET_SCHEDULE[0]->sun == "1" || $GET_SCHEDULE[0]->sun == "2"){
    
                    $sun_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_in));
                    $sun_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_out));
    
                    $sun = $sun_in . " to " . $sun_out;
                }
                else{
    
                    $sun = "Rest Day";
                }
    
            }
            else if($GET_SCHEDULE[0]->type == "Flexi Shift"){
    
                //monday
                if($GET_SCHEDULE[0]->mon == "1" || $GET_SCHEDULE[0]->mon == "2"){
    
                    $mon_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_in));
                    $mon_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_out));
    
                    $mon = $mon_in . " to " . $mon_in;
                }
                else{
    
                    $mon = "Rest Day";
                }
                //tuesday
                if($GET_SCHEDULE[0]->tue == "1" || $GET_SCHEDULE[0]->tue == "2"){
    
                    $tue_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_in));
                    $tue_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_out));
    
                    $tue = $tue_in . " to " . $tue_out;
                }
                else{
    
                    $tue = "Rest Day";
                }
                //wednesday
                if($GET_SCHEDULE[0]->wed == "1" || $GET_SCHEDULE[0]->wed == "2"){
    
                    $wed_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_in));
                    $wed_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_out));
    
                    $wed = $wed_in . " to " . $wed_out;
                }
                else{
    
                    $wed = "Rest Day";
                }
                //thursday
                if($GET_SCHEDULE[0]->thu == "1" || $GET_SCHEDULE[0]->thu == "2"){
    
                    $thu_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_in));
                    $thu_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_out));
    
                    $thu = $thu_in . " to " . $thu_out;
                }
                else{
    
                    $thu = "Rest Day";
                }
                //friday
                if($GET_SCHEDULE[0]->fri == "1" || $GET_SCHEDULE[0]->fri == "2"){
    
                    $fri_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_in));
                    $fri_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_out));
    
                    $fri = $fri_in . " to " . $fri_out;
                }
                else{
    
                    $fri = "Rest Day";
                }
                //saturday
                if($GET_SCHEDULE[0]->sat == "1" || $GET_SCHEDULE[0]->sat == "2"){
    
                    $sat_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_in));
                    $sat_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_out));
    
                    $sat = $sat_in . " to " . $sat_out;
                }
                else{
    
                    $sat = "Rest Day";
                }
                //sunday
                if($GET_SCHEDULE[0]->sun == "1" || $GET_SCHEDULE[0]->sun == "2"){
    
                    $sun_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_in));
                    $sun_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_out));
    
                    $sun = $sun_in . " to " . $sun_out;
                }
                else{
    
                    $sun = "Rest Day";
                }
            }
            else if($GET_SCHEDULE[0]->type == "Free Shift"){
    
                //monday
                if($GET_SCHEDULE[0]->mon == "1" || $GET_SCHEDULE[0]->mon == "2"){
    
                    $mon_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_in));
                    $mon_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->mon_out));
    
                    $mon = $mon_in . " to " . $mon_in;
                }
                else{
    
                    $mon = "Rest Day";
                }
                //tuesday
                if($GET_SCHEDULE[0]->tue == "1" || $GET_SCHEDULE[0]->tue == "2"){
    
                    $tue_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_in));
                    $tue_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->tue_out));
    
                    $tue = $tue_in . " to " . $tue_out;
                }
                else{
    
                    $tue = "Rest Day";
                }
                //wednesday
                if($GET_SCHEDULE[0]->wed == "1" || $GET_SCHEDULE[0]->wed == "2"){
    
                    $wed_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_in));
                    $wed_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->wed_out));
    
                    $wed = $wed_in . " to " . $wed_out;
                }
                else{
    
                    $wed = "Rest Day";
                }
                //thursday
                if($GET_SCHEDULE[0]->thu == "1" || $GET_SCHEDULE[0]->thu == "2"){
    
                    $thu_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_in));
                    $thu_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->thu_out));
    
                    $thu = $thu_in . " to " . $thu_out;
                }
                else{
    
                    $thu = "Rest Day";
                }
                //friday
                if($GET_SCHEDULE[0]->fri == "1" || $GET_SCHEDULE[0]->fri == "2"){
    
                    $fri_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_in));
                    $fri_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->fri_out));
    
                    $fri = $fri_in . " to " . $fri_out;
                }
                else{
    
                    $fri = "Rest Day";
                }
                //saturday
                if($GET_SCHEDULE[0]->sat == "1" || $GET_SCHEDULE[0]->sat == "2"){
    
                    $sat_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_in));
                    $sat_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sat_out));
    
                    $sat = $sat_in . " to " . $sat_out;
                }
                else{
    
                    $sat = "Rest Day";
                }
                //sunday
                if($GET_SCHEDULE[0]->sun == "1" || $GET_SCHEDULE[0]->sun == "2"){
    
                    $sun_in = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_in));
                    $sun_out = date("g:i:a", strtotime($GET_SCHEDULE[0]->sun_out));
    
                    $sun = $sun_in . " to " . $sun_out;
                }
                else{
    
                    $sun = "Rest Day";
                }
            }
        }
        else{

            $sched_type = "--";
            $lunch_out = "--";
            $lunch_in = "--";
            $mon = "--";
            $tue = "--";
            $wed = "--";
            $thu = "--";
            $fri = "--";
            $sat = "--";
            $sun = "--";
        }

        $schedule = array(
            'sched_type'=>$sched_type,
            'lunch_out'=>$lunch_out,
            'lunch_in'=>$lunch_in,
            'mon'=>$mon,
            'tue'=>$tue,
            'wed'=>$wed,
            'thu'=>$thu,
            'fri'=>$fri,
            'sat'=>$sat,
            'sun'=>$sun,
        );
        echo json_encode($schedule);
    }

    public function getEmployeeSchedule()
    {
        $data = "";

        $GET_EMPLOYEE_SCHEDULE_QUERY = "SELECT * FROM view_employee_schedule ORDER BY fullname";
        $GET_EMPLOYEE_SCHEDULE = DB::connection('mysql')->select($GET_EMPLOYEE_SCHEDULE_QUERY);

        $data .= '<table id="tableEmployeeList" name="tableEmployeeList" class="table table-hover" cellspacing="0" style="width:100%" >
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="8" class="text-center">EMPLOYEE LIST</th>
                        </tr>
                        <tr>
                            <th style="width:5px;"></th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Template</th>
                            <th>Shift Time</th>
                            <th>Day</th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($GET_EMPLOYEE_SCHEDULE) > 0){
            foreach($GET_EMPLOYEE_SCHEDULE as $es){
                $data .= '<tr>';
                $data .= '<td> 
                            <input id="empList' . $i . '" name="empList' . $i . '" type="checkbox"></td>
                            <input id="empListVal' . $i . '" name="empListVal' . $i . '" type="hidden" value="' . $es->company_id . '"></td>';
                $data .= '<td>' . $es->company_id . '</td>';
                $data .= '<td>
                            <input type="text" style="display:none" value="' . $es->fullname . '" id="fname' . $i .'" name="fname' . $i .'">' . $es->fullname . '</td>';
                $data .= '<td>' . $es->company_name . '</td>';
                $data .= '<td>' . $es->department . '</td>';
                
                //reg
                if($es->type == "Regular Shift"){

                    $day = "";

                    $reg_in = date("g:i:a", strtotime($es->reg_in));
                    $reg_out = date("g:i:a", strtotime($es->reg_out));
        
                    $shift_time = $reg_in . " to " . $reg_out;

                    if($es->mon == "1" || $es->mon == "2"){
                        
                        $day .= "Mon,";
                    }
                    if($es->tue == "1" || $es->tue == "2"){

                        $day .= "Tue,";
                    }
                    if($es->wed == "1" || $es->wed == "2"){

                        $day .= "Wed,";
                    }
                    if($es->thu == "1" || $es->thu == "2"){

                        $day .= "Thu,";
                    }
                    if($es->fri == "1" || $es->fri == "2"){

                        $day .= "Fri,";
                    }
                    if($es->sat == "1" || $es->sat == "2"){

                        $day .= "Sat,";
                    }
                    if($es->sun == "1" || $es->sun == "2"){

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $es->type . '</td>';
                    $data .= '<td>' . $shift_time . '</td>';
                    $data .= '<td>' . $day . '</td>';
                }
                //irreg
                else if($es->type == "Irregular Shift"){

                    $day = "";
                    $shift_time = "";
                    if($es->mon == "1" || $es->mon == "2"){

                        $mon_in = date("g:i:a", strtotime($es->mon_in));
                        $mon_out = date("g:i:a", strtotime($es->mon_out));
        
                        $shift_time .= "<p> Mon : " . $mon_in . " to " . $mon_out . "<p>";
                        
                        $day .= "Mon,";
                    }
                    if($es->tue == "1" || $es->tue == "2"){

                        $tue_in = date("g:i:a", strtotime($es->tue_in));
                        $tue_out = date("g:i:a", strtotime($es->tue_out));
        
                        $shift_time .= "Tue : " . $tue_in . " to " . $tue_out . "<p>";

                        $day .= "Tue,";
                    }
                    if($es->wed == "1" || $es->wed == "2"){

                        $wed_in = date("g:i:a", strtotime($es->wed_in));
                        $wed_out = date("g:i:a", strtotime($es->wed_out));
        
                        $shift_time .= "Wed : " . $wed_in . " to " . $wed_out . "<p>";

                        $day .= "Wed,";
                    }
                    if($es->thu == "1" || $es->thu == "2"){

                        $thu_in = date("g:i:a", strtotime($es->thu_in));
                        $thu_out = date("g:i:a", strtotime($es->thu_out));
        
                        $shift_time .= "Thu : " . $thu_in . " to " . $thu_out . "<p>";

                        $day .= "Thu,";
                    }
                    if($es->fri == "1" || $es->fri == "2"){

                        $fri_in = date("g:i:a", strtotime($es->fri_in));
                        $fri_out = date("g:i:a", strtotime($es->fri_out));
        
                        $shift_time .= "Fri : " . $fri_in . " to " . $fri_out . "<p>";

                        $day .= "Fri,";
                    }
                    if($es->sat == "1" || $es->sat == "2"){

                        $sat_in = date("g:i:a", strtotime($es->sat_in));
                        $sat_out = date("g:i:a", strtotime($es->sat_out));
        
                        $shift_time .= "Sat : " . $sat_in . " to " . $sat_out . "<p>";

                        $day .= "Sat,";
                    }
                    if($es->sun == "1" || $es->sun == "2"){

                        $sun_in = date("g:i:a", strtotime($es->sun_in));
                        $sun_out = date("g:i:a", strtotime($es->sun_out));
        
                        $shift_time .= "Sun : " . $sun_in . " to " . $sun_out . "<p>";

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $es->type . '</td>';
                    $data .= '<td style="font-size:12px">' . $shift_time . '</td>';
                    $data .= '<td>' . $day . '</td>';
                }
                else if($es->type == "Flexi Shift"){

                }
                else if($es->type == "Free Shift"){

                }
                else{
                    $data .= '<td style="color:#dc3545"><i class="fa fa-times" aria-hidden="true"></i><b>&nbsp;NO SCHEDULE</b></td>';
                    $data .= '<td style="color:#dc3545"><i class="fa fa-times" aria-hidden="true"></i><b>&nbsp;NO SCHEDULE</b></td>';
                    $data .= '<td style="color:#dc3545"><i class="fa fa-times" aria-hidden="true"></i><b>&nbsp;NO SCHEDULE</b></td>';
                }
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>
                    <input id="tblCount" name="tblCount" type="hidden" value="' . $i . '">';
        }
        echo $data;
    }

    public function searchEmployeeSchedule(Request $request)
    {
        $data = "";

        $SEARCH_EMPLOYEE_SCHEDULE_QUERY = "SELECT * FROM view_employee_schedule";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " ORDER BY fullname";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' ORDER BY fullname";
                }
                else{
                    $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' ORDER BY fullname";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                    else{
                        $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMPLOYEE_SCHEDULE_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname"; 
                        }
                    }
                }
            }
        }
        $SEARCH_EMPLOYEE_SCHEDULE = DB::connection('mysql')->select($SEARCH_EMPLOYEE_SCHEDULE_QUERY);

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
                            <th>Template</th>
                            <th>Shift Time</th>
                            <th>Day</th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($SEARCH_EMPLOYEE_SCHEDULE) > 0){
            foreach($SEARCH_EMPLOYEE_SCHEDULE as $es){
                $data .= '<tr>';
                $data .= '<td> 
                            <input id="empList' . $i . '" name="empList' . $i . '" type="checkbox"></td>
                            <input id="empListVal' . $i . '" name="empListVal' . $i . '" type="hidden" value="' . $es->company_id . '"></td>';
                $data .= '<td>' . $es->company_id . '</td>';
                $data .= '<td>
                            <input type="text" style="display:none" value="' . $es->fullname . '" id="fname' . $i .'" name="fname' . $i .'">' . $es->fullname . '</td>';
                $data .= '<td>' . $es->company_name . '</td>';
                $data .= '<td>' . $es->department . '</td>';
                
                //reg
                if($es->type == "Regular Shift"){

                    $day = "";

                    $reg_in = date("g:i:a", strtotime($es->reg_in));
                    $reg_out = date("g:i:a", strtotime($es->reg_out));
        
                    $shift_time = $reg_in . " to " . $reg_out;

                    if($es->mon == "1" || $es->mon == "2"){
                        
                        $day .= "Mon,";
                    }
                    if($es->tue == "1" || $es->tue == "2"){

                        $day .= "Tue,";
                    }
                    if($es->wed == "1" || $es->wed == "2"){

                        $day .= "Wed,";
                    }
                    if($es->thu == "1" || $es->thu == "2"){

                        $day .= "Thu,";
                    }
                    if($es->fri == "1" || $es->fri == "2"){

                        $day .= "Fri,";
                    }
                    if($es->sat == "1" || $es->sat == "2"){

                        $day .= "Sat,";
                    }
                    if($es->sun == "1" || $es->sun == "2"){

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $es->type . '</td>';
                    $data .= '<td>' . $shift_time . '</td>';
                    $data .= '<td>' . $day . '</td>';
                }
                //irreg
                else if($es->type == "Irregular Shift"){

                    $day = "";
                    $shift_time = "";
                    if($es->mon == "1" || $es->mon == "2"){

                        $mon_in = date("g:i:a", strtotime($es->mon_in));
                        $mon_out = date("g:i:a", strtotime($es->mon_out));
        
                        $shift_time .= "<p> Mon : " . $mon_in . " to " . $mon_out . "<p>";
                        
                        $day .= "Mon,";
                    }
                    if($es->tue == "1" || $es->tue == "2"){

                        $tue_in = date("g:i:a", strtotime($es->tue_in));
                        $tue_out = date("g:i:a", strtotime($es->tue_out));
        
                        $shift_time .= "Tue : " . $tue_in . " to " . $tue_out . "<p>";

                        $day .= "Tue,";
                    }
                    if($es->wed == "1" || $es->wed == "2"){

                        $wed_in = date("g:i:a", strtotime($es->wed_in));
                        $wed_out = date("g:i:a", strtotime($es->wed_out));
        
                        $shift_time .= "Wed : " . $wed_in . " to " . $wed_out . "<p>";

                        $day .= "Wed,";
                    }
                    if($es->thu == "1" || $es->thu == "2"){

                        $thu_in = date("g:i:a", strtotime($es->thu_in));
                        $thu_out = date("g:i:a", strtotime($es->thu_out));
        
                        $shift_time .= "Thu : " . $thu_in . " to " . $thu_out . "<p>";

                        $day .= "Thu,";
                    }
                    if($es->fri == "1" || $es->fri == "2"){

                        $fri_in = date("g:i:a", strtotime($es->fri_in));
                        $fri_out = date("g:i:a", strtotime($es->fri_out));
        
                        $shift_time .= "Fri : " . $fri_in . " to " . $fri_out . "<p>";

                        $day .= "Fri,";
                    }
                    if($es->sat == "1" || $es->sat == "2"){

                        $sat_in = date("g:i:a", strtotime($es->sat_in));
                        $sat_out = date("g:i:a", strtotime($es->sat_out));
        
                        $shift_time .= "Sat : " . $sat_in . " to " . $sat_out . "<p>";

                        $day .= "Sat,";
                    }
                    if($es->sun == "1" || $es->sun == "2"){

                        $sun_in = date("g:i:a", strtotime($es->sun_in));
                        $sun_out = date("g:i:a", strtotime($es->sun_out));
        
                        $shift_time .= "Sun : " . $sun_in . " to " . $sun_out . "<p>";

                        $day .= "Sun";
                    }

                    $data .= '<td>' . $es->type . '</td>';
                    $data .= '<td style="font-size:12px">' . $shift_time . '</td>';
                    $data .= '<td>' . $day . '</td>';
                }
                else if($es->type == "Flexi Shift"){

                }
                else if($es->type == "Free Shift"){

                }
                else{
                    $data .= '<td style="color:red">NO SCHEDULE</td>';
                    $data .= '<td style="color:red">NO SCHEDULE</td>';
                    $data .= '<td style="color:red">NO SCHEDULE</td>';
                }
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>
                    <input id="tblCount" name="tblCount" type="hidden" value="' . $i . '">';
        }
        echo $data;
    }

    public function searchEmployeeScheduleRequestRecord(Request $request)
    {
        $data = "";

        $SEARCH_EMP_SCHED_RECORD_QUERY = "SELECT * FROM view_employee_schedule_request";

        if($request->lastname == "" && $request->firstname == "" && $request->company == "" && $request->department == "" && $request->team == "" && $request->status == ""){
            $SEARCH_EMP_SCHED_RECORD_QUERY .= " ORDER BY fullname";
        }
        else{
            if($request->company == ""){
                if($request->status == ""){
                     $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' ORDER BY fullname";
                }
                else{
                    $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' ORDER BY fullname";
                }
            }
            else{
                if($request->department == ""){
                    if($request->status == ""){
                        $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                    else{
                        $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' ORDER BY fullname";
                    }
                }
                else{
                    if($request->team == ""){
                        if($request->status == ""){
                            $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "' AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND employment_status = '" . $request->status . "' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' ORDER BY fullname";
                        }
                    }
                    else{
                        if($request->status == ""){
                            $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname";
                        }
                        else{
                            $SEARCH_EMP_SCHED_RECORD_QUERY .= " WHERE lname LIKE '%$request->lastname%' AND fname LIKE '%$request->firstname%' AND company_ind = '" . $request->company . "'  AND department = '" . $request->department . "' AND team = '" . $request->team . "' ORDER BY fullname"; 
                        }
                    }
                }
            }
        }
        $SEARCH_EMP_SCHED_RECORD = DB::connection('mysql')->select($SEARCH_EMP_SCHED_RECORD_QUERY);

        $data = '<table id="tableScheduleRequestRecord" name="tableScheduleRequestRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="8" class="text-center">SCHEDULE RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Shift Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($SEARCH_EMP_SCHED_RECORD) > 0){          
            foreach($SEARCH_EMP_SCHED_RECORD as $esr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $esr->company_id . '</td>';
                $data .= '<td>' . $esr->fullname . '</td>';
                $data .= '<td>' . $esr->company_name . '</td>';             
                $data .= '<td>' . $esr->department . '</td>';
                $data .= '<td>' . $esr->type . '</td>';
                $data .= '<td>' . $esr->start_date . '</td>';
                $data .= '<td>' . $esr->end_date . '</td>';
                if($esr->request_status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-ban"></i><b>CANCELLED</b></td>';
                }
                else{

                    $data .= '<td><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $esr->id .'" value="Cancel Schedule Request"></td>';
                }
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
            </table>';
        }
        echo $data;
    }

    public function checkScheduleRequest(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();
        

        if($request->input('cmbScheduleType') == ""){

            $message = "Please select Schedule Type!";
            $error[] = $message;
        }
        else if($request->input('startDate') == ""){

            $message = "Start Date field is required!";
            $error[] = $message;
        }
        else if($request->input('endDate') == ""){

            $message = "End Date field is required!";
            $error[] = $message;
        }
        else if($request->input('startDate') > $request->input('endDate')){

            $message = "End Date must greater than Start Date!";
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

    public function addScheduleRequest(Request $request)
    {
        $result = array();
        $success = array();

        $start_date = date("Y-m-d", strtotime($request->input('startDate')));
        $end_date = date("Y-m-d", strtotime($request->input('startDate')));

        for($i = 1; $i <= $request->input('tblCount'); $i++){
            if($request->input('empList' . $i)){

                if($request->input('empList' . $i) == "on"){

                    $insert_query = new ScheduleRequestRecords;
                    $insert_query->company_id = $request->input('empListVal' . $i);
                    $insert_query->template_id = $request->input('cmbScheduleType');
                    $insert_query->start_date = $start_date;
                    $insert_query->end_date = $end_date;
                    $insert_query->request_status = "APPROVED";
                    $insert_query->approved_1 = 1;
                    $insert_query->approved_2 = 1;
                    $insert_query->approved_1_id = auth()->user()->company_id;
                    $insert_query->approved_2_id = auth()->user()->company_id;
                    $insert_query->created_by = auth()->user()->name;
                    $insert_query->lu_by = auth()->user()->name;
                    $insert_query->timestamps = false;
                    $insert_query->save();

                    $message = $request->input('empListVal' . $i) . "]]" . $request->input('fname' . $i) . "]]" . "Schedule Request successfully apply!";
                    $success[] = $message;
                }
            }
        }
        $result = array(
            'success'=>$success,
        );
        echo json_encode($result);
    }

    public function getScheduleRequestRecord(Request $request)
    {
        $data = "";

        $GET_SCHED_REQ_RECORD_QUERY = "SELECT * FROM view_employee_schedule_request";
        $GET_SCHED_REQ_RECORD = DB::connection('mysql')->select($GET_SCHED_REQ_RECORD_QUERY);

        $data = '<table id="tableScheduleRequestRecord" name="tableScheduleRequestRecord" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr class="header" style="background:#f7f7f7;">
                            <th colspan="8" class="text-center">SCHEDULE RECORDS</th>
                        </tr>
                        <tr>
                            <th>Company ID</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Shift Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($GET_SCHED_REQ_RECORD) > 0){          
            foreach($GET_SCHED_REQ_RECORD as $srr){  
                
                $data .= '<tr>';
                $data .= '<td>' . $srr->company_id . '</td>';
                $data .= '<td>' . $srr->fullname . '</td>';
                $data .= '<td>' . $srr->company_name . '</td>';
                $data .= '<td>' . $srr->department . '</td>';             
                $data .= '<td>' . $srr->type . '</td>';
                $data .= '<td>' . $srr->start_date . '</td>';
                $data .= '<td>' . $srr->end_date . '</td>';
                if($srr->request_status == "CANCELLED"){
                    $data .= '<td style="color:#dc3545";><i class="icon-right fa fa-ban"></i><b>CANCELLED</b></td>';
                }
                else{

                    $data .= '<td><input type="button" class="btn btn-sm button red btnCancel" data-add="' . $srr->id .'" value="Cancel Schedule Request"></td>';
                }
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
            </table>';
        }
        echo $data;
    }

    public function cancelScheduleRequest(Request $request)
    {
            $update_query = ScheduleRequestRecords::find($request->id);
            $update_query->request_status = 'CANCELLED';
            $update_query->lu_by = auth()->user()->name;
            $update_query->timestamps = false;
            $update_query->save();
    }

    public function checkScheduleTemplate(Request $request)
    {
        $valid = "true";
        $proceed = "true";

        $result = array();
        $error = array();
        $success = array();

        $query = "SELECT template FROM schedule_template WHERE template = '" . $request->input('txtTemplateName') . "'";
        $select_query = DB::connection('mysql3')->select($query);

        if($request->input('txtTemplateName') == ""){
            $message = "Template Name field is required!";
            $error[] = $message;

            $proceed = "false";
        }
        else if(!empty($select_query)){
            $message = "Template Name already exist!";
            $error[] = $message;

            $proceed = "false";
        }
        else if($request->input('addLunchOut') >= $request->input('addLunchIn') && ($request->input('addLunchOut') != "" && $request->input('addLunchIn') != "")){
            $message = "Lunch Out must be greater than Lunch In";
            $error[] = $message;

            $proceed = "false";
        }

        //validation for regular shift
        if($request->input('rdShiftType') == "Regular Shift" && $proceed == "true"){
            if($request->input('regShiftIn') == ""){
                $message = "Shift In field is required!";
                $error[] = $message;
            }
            else if($request->input('regShiftOut') == ""){
                $message = "Shift Out field is required!";
                $error[] = $message;
            }
            else{
                $message = "1";
                $success[] = $message;
            }
        }
        //validation for irregular shift
        if($request->input('rdShiftType') == "Irregular Shift" && $proceed == "true"){

            //irreg monday
            if($request->input('chkIrregMon') == "on"){
                if($request->input('monIrregTimeIn') == ""){
                    $message = "Monday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('monIrregTimeOut') == ""){
                    $message = "Monday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('monIrregTimeIn') >= $request->input('monIrregTimeOut')){
                    $message = "Monday Shift out must be greater than Monday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            //irreg tuesday
            if($request->input('chkIrregTue') == "on"){
                if($request->input('tueIrregTimeIn') == ""){
                    $message = "Tuesday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('tueIrregTimeOut') == ""){
                    $message = "Tuesday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('tueIrregTimeIn') >= $request->input('tueIrregTimeOut')){
                    $message = "Tuesday Shift out must be greater than Tuesday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            //irreg wednesday
            if($request->input('chkIrregWed') == "on"){
                if($request->input('wedIrregTimeIn') == ""){
                    $message = "Wednesday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('wedIrregTimeOut') == ""){
                    $message = "Wednesday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('wedIrregTimeIn') >= $request->input('wedIrregTimeOut')){
                    $message = "Wednesday Shift out must be greater than Wednesday Shift In!";
                    $error[] = $message;
                    
                    $valid = "false";
                }
            }

            //irreg thursday
            if($request->input('chkIrregThu') == "on"){
                if($request->input('thuIrregTimeIn') == ""){
                    $message = "Thursday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('thuIrregTimeOut') == ""){
                    $message = "Thursday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('thuIrregTimeIn') >= $request->input('thuIrregTimeOut')){
                    $message = "Thursday Shift out must be greater than Thursday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            //irreg friday
            if($request->input('chkIrregFri') == "on"){
                if($request->input('friIrregTimeIn') == ""){
                    $message = "Friday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('friIrregTimeOut') == ""){
                    $message = "Friday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('friIrregTimeIn') >= $request->input('friIrregTimeOut')){
                    $message = "Friday Shift out must be greater than Friday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            //irreg saturday
            if($request->input('chkIrregSat') == "on"){
                if($request->input('satIrregTimeIn') == ""){
                    $message = "Saturday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('satIrregTimeOut') == ""){
                    $message = "Saturday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('satIrregTimeIn') >= $request->input('satIrregTimeOut')){
                    $message = "Saturday Shift out must be greater than Saturday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            //irreg sunday
            if($request->input('chkIrregSun') == "on"){
                if($request->input('sunIrregTimeIn') == ""){
                    $message = "Sunday Shift In field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('sunIrregTimeOut') == ""){
                    $message = "Sunday Shift Out field is required!";
                    $error[] = $message;

                    $valid = "false";
                }
                else if($request->input('sunIrregTimeIn') >= $request->input('sunIrregTimeOut')){
                    $message = "Sunday Shift out must be greater than Sunday Shift In!";
                    $error[] = $message;

                    $valid = "false";
                }
            }

            if($valid == "true"){
                $message = "1";
                $success[] = $message;
            }
        }
        //validation for flexi shift
        if($request->input('rdShiftType') == "Flexi Shift" && $proceed == "true"){

            if($request->input('txtHoursDuration') == ""){
                
                $message = "Hours To Complete field is required!";
                $error[] = $message;
            }
            else{
                $message = "1";
                $success[] = $message;
            }
        }

        $result = array(
            'error'=>$error,
            'success'=>$success,
        );
        echo json_encode($result);
    }
    public function addScheduleTemplate(Request $request)
    {
        $lunch_out = date("H:i:s", strtotime($request->input('addLunchOut')));
        $lunch_in = date("H:i:s", strtotime($request->input('addLunchIn')));

        //add regular shift
        if($request->input('rdShiftType') == "Regular Shift"){

            $reg_in = date("H:i:s", strtotime($request->input('regShiftIn')));
            $reg_out = date("H:i:s", strtotime($request->input('regShiftOut')));

            $insert_query = new ScheduleTemplateRecords;
            $insert_query->template = $request->input('txtTemplateName');
            $insert_query->type = $request->input('rdShiftType');
            $insert_query->reg_in = $reg_in;
            $insert_query->reg_out = $reg_out;

            //monday
            if($request->input('chkRegularMon') == "on"){
                if($request->input('chkRegularRestDayMon') == "on"){
                    $insert_query->mon = "2";
                }
                else{
                    $insert_query->mon = "1";
                }   
            }
            else{
                $insert_query->mon = "0";
            }

            //tuesday
            if($request->input('chkRegularTue') == "on"){
                if($request->input('chkRegularRestDayTue') == "on"){
                    $insert_query->tue = "2";
                }
                else{
                    $insert_query->tue = "1";
                }   
            }
            else{
                $insert_query->tue = "0";
            }

            //wednesday
            if($request->input('chkRegularWed') == "on"){
                if($request->input('chkRegularRestDayWed') == "on"){
                    $insert_query->wed = "2";
                }
                else{
                    $insert_query->wed = "1";
                }   
            }
            else{
                $insert_query->wed = "0";
            }

            //thursday
            if($request->input('chkRegularThu') == "on"){
                if($request->input('chkRegularRestDayThu') == "on"){
                    $insert_query->thu = "2";
                }
                else{
                    $insert_query->thu = "1";
                }   
            }
            else{
                $insert_query->thu = "0";
            }

            //friday
            if($request->input('chkRegularFri') == "on"){
                if($request->input('chkRegularRestDayFri') == "on"){
                    $insert_query->fri = "2";
                }
                else{
                    $insert_query->fri = "1";
                }   
            }
            else{
                $insert_query->fri = "0";
            }

            //saturday
            if($request->input('chkRegularSat') == "on"){
                if($request->input('chkRegularRestDaySat') == "on"){
                    $insert_query->sat = "2";
                }
                else{
                    $insert_query->sat = "1";
                }   
            }
            else{
                $insert_query->sat = "0";
            }

            //sunday
            if($request->input('chkRegularSun') == "on"){
                if($request->input('chkRegularRestDaySun') == "on"){
                    $insert_query->sun = "2";
                }
                else{
                    $insert_query->sun = "1";
                }   
            }
            else{
                $insert_query->sun = "0";
            }

            if($request->input('hiddenLunchHours') != ""){
                $insert_query->lunch_out = $lunch_out;
                $insert_query->lunch_in = $lunch_in;
                $insert_query->lunch_hrs = $request->input('hiddenLunchHours');
                
            }
            $insert_query->schedule_desc = $request->input('txtScheduleDesc');
            $insert_query->created_by = auth()->user()->name;
            $insert_query->lu_by = auth()->user()->name;
            $insert_query->timestamps = false;
            $insert_query->save();
        }
        //add irregular shift
        else if($request->input('rdShiftType') == "Irregular Shift"){
            
            $insert_query = new ScheduleTemplateRecords;
            $insert_query->template = $request->input('txtTemplateName');
            $insert_query->type = $request->input('rdShiftType');

            //monday
            if($request->input('chkIrregMon') == "on"){

                 $mon_in = date("H:i:s", strtotime($request->input('monIrregTimeIn')));
                 $mon_out = date("H:i:s", strtotime($request->input('monIrregTimeOut')));

                 $insert_query->mon_in = $mon_in;
                 $insert_query->mon_out = $mon_out;

                 $insert_query->reg_in = null;
                 $insert_query->reg_out = null;

                if($request->input('chkIrregRestDayMon') == "on"){
                    $insert_query->mon = "2";
                }
                else{
                    $insert_query->mon = "1";
                }   
            }
            else{
                $insert_query->mon = "0";
            }

            //tuesday
            if($request->input('chkIrregTue') == "on"){

                $tue_in = date("H:i:s", strtotime($request->input('tueIrregTimeIn')));
                $tue_out = date("H:i:s", strtotime($request->input('tueIrregTimeOut')));

                $insert_query->tue_in = $tue_in;
                $insert_query->tue_out = $tue_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDayTue') == "on"){
                   $insert_query->tue = "2";
               }
               else{
                   $insert_query->tue = "1";
               }   
           }
           else{
               $insert_query->tue = "0";
           }

           //wednesday
            if($request->input('chkIrregWed') == "on"){

                $wed_in = date("H:i:s", strtotime($request->input('wedIrregTimeIn')));
                $wed_out = date("H:i:s", strtotime($request->input('wedIrregTimeOut')));

                $insert_query->wed_in = $wed_in;
                $insert_query->wed_out = $wed_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDayWed') == "on"){
                   $insert_query->wed = "2";
               }
               else{
                   $insert_query->wed = "1";
               }   
           }
           else{
               $insert_query->wed = "0";
           }

           //thursday
            if($request->input('chkIrregThu') == "on"){

                $thu_in = date("H:i:s", strtotime($request->input('thuIrregTimeIn')));
                $thu_out = date("H:i:s", strtotime($request->input('thuIrregTimeOut')));

                $insert_query->thu_in = $thu_in;
                $insert_query->thu_out = $thu_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDayThu') == "on"){
                   $insert_query->thu = "2";
               }
               else{
                   $insert_query->thu = "1";
               }   
           }
           else{
               $insert_query->thu = "0";
           }

           //friday
            if($request->input('chkIrregFri') == "on"){

                $fri_in = date("H:i:s", strtotime($request->input('friIrregTimeIn')));
                $fri_out = date("H:i:s", strtotime($request->input('friIrregTimeOut')));

                $insert_query->fri_in = $fri_in;
                $insert_query->fri_out = $fri_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDayFri') == "on"){
                   $insert_query->fri = "2";
               }
               else{
                   $insert_query->fri = "1";
               }   
           }
           else{
               $insert_query->fri = "0";
           }

           //saturday
            if($request->input('chkIrregSat') == "on"){

                $sat_in = date("H:i:s", strtotime($request->input('satIrregTimeIn')));
                $sat_out = date("H:i:s", strtotime($request->input('satIrregTimeOut')));

                $insert_query->sat_in = $sat_in;
                $insert_query->sat_out = $sat_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDaySat') == "on"){
                   $insert_query->sat = "2";
               }
               else{
                   $insert_query->sat = "1";
               }   
           }
           else{
               $insert_query->sat = "0";
           }

           //saturday
            if($request->input('chkIrregSun') == "on"){

                $sun_in = date("H:i:s", strtotime($request->input('sunIrregTimeIn')));
                $sun_out = date("H:i:s", strtotime($request->input('sunIrregTimeOut')));

                $insert_query->sun_in = $sun_in;
                $insert_query->sun_out = $sun_out;

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

               if($request->input('chkIrregRestDaySun') == "on"){
                   $insert_query->sun = "2";
               }
               else{
                   $insert_query->sun = "1";
               }   
           }
           else{
               $insert_query->sun = "0";
           }

           if($request->input('hiddenLunchHours') != ""){
                $insert_query->lunch_out = $lunch_out;
                $insert_query->lunch_in = $lunch_in;
                $insert_query->lunch_hrs = $request->input('hiddenLunchHours');
                
            }
            $insert_query->schedule_desc = $request->input('txtScheduleDesc');
            $insert_query->created_by = auth()->user()->name;
            $insert_query->lu_by = auth()->user()->name;
            $insert_query->timestamps = false;
            $insert_query->save();
        }

        //add flexi shift
        else if($request->input('rdShiftType') == "Flexi Shift"){

            $insert_query = new ScheduleTemplateRecords;
            $insert_query->template = $request->input('txtTemplateName');
            $insert_query->type = $request->input('rdShiftType');

                $insert_query->reg_in = null;
                $insert_query->reg_out = null;

                $insert_query->mon_in = null;
                $insert_query->mon_out = null;

                $insert_query->tue_in = null;
                $insert_query->tue_out = null;

                $insert_query->wed_in = null;
                $insert_query->wed_out = null;

                $insert_query->thu_in = null;
                $insert_query->thu_out = null;

                $insert_query->fri_in = null;
                $insert_query->fri_out = null;

                $insert_query->sat_in = null;
                $insert_query->sat_out = null;

                $insert_query->sun_in = null;
                $insert_query->sun_out = null;

            //check mon
            if($request->input('chkFlexiMon') == "on"){

                $insert_query->mon = "1";
            }
            else{
                $insert_query->mon = "0";
            }

            //check tue
            if($request->input('chkFlexiTue') == "on"){

                $insert_query->tue = "1";
            }
            else{
                $insert_query->tue = "0";
            }

            //check wed
            if($request->input('chkFlexiWed') == "on"){

                $insert_query->wed = "1";
            }
            else{
                $insert_query->wed = "0";
            }

            //check thu
            if($request->input('chkFlexiThu') == "on"){

                $insert_query->thu = "1";
            }
            else{
                $insert_query->thu = "0";
            }

            //check fri
            if($request->input('chkFlexiFri') == "on"){

                $insert_query->fri = "1";
            }
            else{
                $insert_query->fri = "0";
            }

            //check sat
            if($request->input('chkFlexiSat') == "on"){

                $insert_query->sat = "1";
            }
            else{
                $insert_query->sat = "0";
            }

            //check sun
            if($request->input('chkFlexiSun') == "on"){

                $insert_query->sun = "1";
            }
            else{
                $insert_query->sun = "0";
            }

            if($request->input('hiddenLunchHours') != ""){
                $insert_query->lunch_out = $lunch_out;
                $insert_query->lunch_in = $lunch_in;
                $insert_query->lunch_hrs = $request->input('hiddenLunchHours');
                
            }
            $insert_query->schedule_desc = $request->input('txtScheduleDesc');
            $insert_query->flexihrs = $request->input('txtHoursDuration');
            $insert_query->created_by = auth()->user()->name;
            $insert_query->lu_by = auth()->user()->name;
            $insert_query->timestamps = false;
            $insert_query->save();
        }
    }

}
