<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use App\Models\AlterationRecords;
use Validator;
use App\Models\DateTimeRecords;

class TimeRecordsController extends Controller
{
    public function index()
    {
        $time_record = DateTimeRecords::where('company_id', auth()->user()->company_id)->get();
        //$time_record = DB::connection('mysql2')->select('SELECT sched_date,date_time_in,date_time_out,reg_total_hrs FROM date_and_time_records WHERE employee_name = "Agoncillo, Judy Ann" ORDER BY sched_date DESC');
        return view('modules.usersmodule.timerecords.timerecords')->with('time_record', $time_record);
    }
    
    //show table
    public function print_date_now()
    {
        //Code for getting the current date of Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $todays_date = date("y-m-d");
            $today = strtotime($todays_date);
            $todayDate = date("Y-m-d", $today); 
        //Code for getting the current date of Asia/Manila

        $getdatetimein = "";
        $getdatetimeout = "";
        $timein = "";
        $timeout = "";
        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');
        
        $dates = "";
        $current_date = new DateTime();
        $date_altered = "";
        $data = '<table id="table_time_records" name="table_time_records" class="table table-hover" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Total Hours</th>
                <th>Undertime</th>
                <th>Late</th>
                <th>Holiday</th>
                <th>Remarks</th>
               
            </tr>
        </thead>
        <tbody>';
        $counter = 1;
        for($a = 0; $a <= 100; $a++)
        {
            $dates = $current_date->format('Y-m-d');
            
            $time_record = DB::connection('mysql2')->select("SELECT sched_date,date_time_in,date_time_out,reg_total_hrs,remarks,ot_total_hrs FROM date_and_time_records WHERE company_id = '".auth()->user()->company_id."' AND sched_date = '$dates' ");

            $holiday_record = DB::connection('mysql2')->select("SELECT * FROM holidays WHERE holiday_date = '$dates'");

            $alter_record = DB::connection('mysql')->select("SELECT * FROM alteration_records WHERE company_id = '".auth()->user()->company_id."' AND sched_date = '$dates' AND (status != 'CANCELLED' AND status != 'REJECTED')");
            
            //kung may laman sa dtr table from payroll
            if(!empty($time_record))
            {   
                //may record
                //Getting the time previous time in and out on the time records table and convert it into formatted type
                    $timein = $time_record[0]->date_time_in;
                    $date = date("Y-m-d", strtotime("$timein")); 
                    $date2 = date("H:i", strtotime("$timein"));
                    $getdatetimein =  $date . 'T' . $date2;

                    $timeout = $time_record[0]->date_time_out;
                    $date = date("Y-m-d", strtotime("$timeout")); 
                    $date2 = date("H:i", strtotime("$timeout"));
                    $getdatetimeout =  $date . 'T' . $date2;
                //end
                
                $data .= "<input type='text' style='display:none' value='".$time_record[0]->sched_date."]]".$timein."]]".$timeout."' name='infos".$counter."' id='infos".$counter."' >"; //hidden value of the date of you want to alter and previous time in and out
                $data .= "<tr>";
                //check nya kung may value na sa alter table kung meron na hindi na sya machecheck or else checkable 
                
                if(!empty($alter_record))
                {
                    $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" disabled></td>';
                }
                else
                {
                    if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" disabled></td>';
                    }else{
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'"></td>';
                    }
                }

                //end
                $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($time_record[0]->sched_date))."</a>
                <br>
                <small>".date("l",strtotime($time_record[0]->sched_date))."</small>
                </td>";
                    
                //check kung may laman ang previous time in sa dtr kung walang laman
                if($time_record[0]->date_time_in == "")
                {
                    //wala syang laman or walang ipriprint
                    if(!empty($alter_record)) //Checheck nya kung may na alter na sa table alter table
                    {
                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2;
                        
                        //print nya lang ung laman sa alter table
                        $data .= "
                        <td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>
                        ";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "
                        <td style='width:10%;'>
                        <input type='datetime-local' value='' name='timein".$counter."' class='form-control datetime' id='timein".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "
                        <td style='width:10%;'>
                        <input type='datetime-local' value='' name='timein".$counter."' class='form-control datetime' id='timein".$counter."' required>
                        </td>";
                        }
                    }   
                     
                }
                else //kung may laman sa dtr table priprint nya lang dito = ok
                {
                    if(!empty($alter_record)) // checheck nya kung may na alter na sa table alter table
                    {  
                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2;

                        //print nya lang ung laman sa alter table
                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='".$getdatetimein."' name='timein".$counter."' class='form-control datetime' id='timein".$counter."' required disabled>
                            </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='".$getdatetimein."' name='timein".$counter."' class='form-control datetime' id='timein".$counter."' required>
                            </td>";
                        }
                    }   
                }


                //same sa taas
                if($time_record[0]->date_time_out == "")
                {        
                    if(!empty($alter_record))
                    {  
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimeout ."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>"; 
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>"; 
                        }
                    }   

                }
                else
                {
                    if(!empty($alter_record))
                    {  
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        $data .= "<td style='width:10%;'><input type='datetime-local' value='". $getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled></td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "<td style='width:10%;'><input type='datetime-local' value='".$getdatetimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled></td>"; 
                        }else{
                            $data .= "<td style='width:10%;'><input type='datetime-local' value='".$getdatetimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required></td>"; 
                        }
                    }     
                }
                
                //check sa total hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->total_hrs . "</td>";
                }else{
                    $data .= "<td>". $time_record[0]->reg_total_hrs ."</td>";
                }

                //check sa undertime hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->undertime ."</td>";
                }else{
                    $data .= "<td>0.00</td>";
                }

                //check sa late hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->late ."</td>";
                }else{
                    $data .= "<td>0.00</td>";
                }

                //Check if Holidays matches the sched date
                $restday = date("l",strtotime($time_record[0]->sched_date));
                if(!empty($holiday_record))
                {
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ." (Rest Day)</em></td>";
                    }else{
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ."</em></td>";
                    }
                }else{
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td><em>Rest Day</em></td>";
                    }else{
                        $data .= "<td></td>";
                    }
                }

                
                //check kung may laman sa alter record table
                if(!empty($alter_record))
                {  
                    //kung may laman punch altered ang print sa remarks
                    $data .= "<td>
                    <input type='text' style='background-color:transparent; font-weight: bold; text-align:center;' value='Punch Altered' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' disabled>
                    </td>";
                }
                else
                {
                    //kung wala, editable ang txtremarks para sa pag sasave
                    $data .= "<td><input style='background-color:transparent; font-weight: bold; text-align:center; display:none;' type='text' value='DTR' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' disabled>". $time_record[0]->remarks ."</td>";
                }
               
                $data .= "</tr>";
                //end may record
            }

            //kung walang laman sa dtr table from payroll absent sya then
            else
            {

                //absent
                $data .= "<input type='text' style='display:none' value='".$dates."]]]]' name='infos".$counter."' id='infos".$counter."' >"; //kinukuha nya yung date ng gusto nyang ialter at dahil absent sya null and prev time and out nya
                $data .= "<tr>";
                 //check nya kung may value na sa alter table kung meron na hindi na sya machecheck or else checkable 
                
                
                
                if(!empty($alter_record))
                {
                    $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled></td>';
                }
                else
                {
                    if($todayDate == date("Y-m-d",strtotime($dates))){
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled></td>';
                    }else{
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required></td>';
                    }
                }

                //end
                $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($dates))."</a>
                <br>
                <small>".date("l",strtotime($dates))."</small>
                </td>";
                
                //check nya yung kung may previous time in sya
                if($timein == "")
                {
                    if(!empty($alter_record))
                    { 
                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2; 
                        
                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required>
                        </td>";
                        }
                    }
                }
                else
                {
                    if(!empty($alter_record)){

                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2; 

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }

                    }else{

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required >
                            </td>";
                        }

                    }
                    
                }

                 //check nya yung kung may previous time in sya
                if($timeout == "")
                {
                    if(!empty($alter_record))
                    {  
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>";
                        }
                    }
                }
                else
                {

                    if(!empty($alter_record)){
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }

                    }else{
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>";
                        }
                    }
                }
               
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->total_hrs ."</td>";
                }
                else{
                    $data .= "<td></td>";
                }
                //check sa undertime hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->undertime ."</td>";
                }else{
                    $data .= "<td></td>";
                }

                //check sa late hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->late ."</td>";
                }else{
                    $data .= "<td></td>";
                }
                
                //Check if Holidays matches the sched date
                $restday = date("l",strtotime($dates));
                if(!empty($holiday_record))
                {
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ." (Rest Day)</em></td>";
                    }else{
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ."</em></td>";
                    }
                }else{
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td><em>Rest Day</em></td>";
                    }else{
                        $data .= "<td></td>";
                    }
                }


                if(!empty($alter_record))
                {
                    $data .= "<td>
                    <input type='text' style='background-color:transparent; font-weight: bold; text-align:center;' value='Punch Altered' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' disabled>
                    <input type='text' style='display:none;' value='Punch Altered' name='validateStatus".$counter."' id='validateStatus".$counter."'>
                    </td>";
                }
                else{
                    $data .= "<td style='color: #dc3545;'>
                    <input style='background-color:transparent; font-weight: bold; text-align:center; color:#dc3545' type='text' value='Absent' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' disabled>
                    <input type='text' style='display:none;' value='Absent' name='validateStatus".$counter."' id='validateStatus".$counter."'>
                    </td>";
                }
                $data .= "</tr>";
                //end absent
            }
            $counter++;
            $current_date->modify('-1 day');     
        }

        $data .= "</tbody>
                </table>";

        echo $data;
    }  

    //filter dates and show it on table/
    public function filter_dates(Request $request)
    {
        //Code for getting the current date of Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $todays_date = date("y-m-d");
            $today = strtotime($todays_date);
            $todayDate = date("Y-m-d", $today); 
        //Code for getting the current date of Asia/Manila

        $date_now = new DateTime();
        $date_now = $date_now->format('Y-m-d');

        $getdatetimein = "";
        $getdatetimeout = "";
        $timein = "";
        $timeout = "";
        
        $date_altered = "";
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $start_date = date("Y-m-d", strtotime("$start_date"));
        $end_date = date("Y-m-d", strtotime("$end_date"));

        $data = '<table id="table_time_records" name="table_time_records" class="table table-hover" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Total Hours</th> 
                <th>Undertime</th>
                <th>Late</th>
                <th>Holiday</th>
                <th>Remarks</th> 
            </tr>
        </thead>
        <tbody>';
        $counter = 1;
        while($start_date <= $end_date)
        {
            $dates = date("Y-m-d", strtotime("$start_date"));

            $time_record = DB::connection('mysql2')->select("SELECT sched_date,date_time_in,date_time_out,reg_total_hrs,remarks,ot_total_hrs FROM date_and_time_records WHERE company_id = '".auth()->user()->company_id."' AND sched_date = '$dates' ");

            $holiday_record = DB::connection('mysql2')->select("SELECT * FROM holidays WHERE holiday_date = '$dates'");

            $alter_record = DB::connection('mysql')->select("SELECT * FROM alteration_records WHERE company_id = '".auth()->user()->company_id."' AND sched_date = '$dates' AND (status != 'CANCELLED' AND status != 'REJECTED')");
            //may record
            if(!empty($time_record))
            {
                //getting the time previous time in and out on the time records table and convert it into formatted type
                $timein = $time_record[0]->date_time_in;
                $date = date("Y-m-d", strtotime("$timein")); 
                $date2 = date("H:i", strtotime("$timein"));
                $getdatetimein =  $date . 'T' . $date2;

                $timeout = $time_record[0]->date_time_out;
                $date = date("Y-m-d", strtotime("$timeout")); 
                $date2 = date("H:i", strtotime("$timeout"));
                $getdatetimeout =  $date . 'T' . $date2;
                //end

                $data .= "<input type='text' style='display:none' value='".$time_record[0]->sched_date."]]".$timein."]]".$timeout."' name='infos".$counter."' id='infos".$counter."' >"; //hidden value of the date of you want to alter and previous time in and out
                $data .= "<tr>";
                
                //check nya kung may value na sa alter table kung meron na hindi na sya machecheck or else checkable 
                if(!empty($alter_record))
                {
                    $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled/></td>';
                }
                else
                {
                    if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled></td>';
                    }else{
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required /></td>';
                    }
                }
                //end
                

                $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($time_record[0]->sched_date))."</a>
                <br>
                <small>".date("l",strtotime($time_record[0]->sched_date))."</small>
                </td>";
               
                //check kung may laman ang previous time in sa dtr kung walang laman
                if($time_record[0]->date_time_in != "")
                {
                    if(!empty($alter_record)) // checheck nya kung may na alter na sa table alter table
                    {  
                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2;
                        //print nya lang ung laman sa alter table
                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getdatetimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getdatetimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required>
                        </td>";
                        }
                    }
                    
                }
                else //kung may laman sa dtr table priprint nya lang dito
                {
                    if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                        //$data .= "<td style='width:10%;'><input type='datetime-local' value='".$getdatetimein."' class='form-control datetime' id='timein".$counter."' required ></td>";
                    $data .= "<td style='width:10%;'>
                    <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                    </td>";
                    }else{
                        //$data .= "<td style='width:10%;'><input type='datetime-local' value='".$getdatetimein."' class='form-control datetime' id='timein".$counter."' required ></td>";
                    $data .= "<td style='width:10%;'>
                    <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required>
                    </td>";
                    }
                }

                //same sa taas sa if
                if($time_record[0]->date_time_out != "")
                {
                    if(!empty($alter_record))
                    {  
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;
                        
                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getdatetimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getdatetimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>";
                        }
                    }
                    
                }
                else{
                    if($todayDate == date("Y-m-d",strtotime($time_record[0]->sched_date))){
                        $data .= "<td style='width:10%;'>
                    <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                    </td>";
                    }else{
                        $data .= "<td style='width:10%;'>
                    <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                    </td>";
                    }
                }
                //total hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->total_hrs ."</td>";
                }else{
                    $data .= "<td>". $time_record[0]->reg_total_hrs ."</td>";
                }
                //check sa undertime hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->undertime ."</td>";
                }else{
                    $data .= "<td></td>";
                }

                //check sa late hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->late ."</td>";
                }else{
                    $data .= "<td></td>";
                }

                //Check if Holidays matches the sched date
                $restday = date("l",strtotime($time_record[0]->sched_date));
                if(!empty($holiday_record))
                {
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ." (Rest Day)</em></td>";
                    }else{
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ."</em></td>";
                    }
                }else{
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td><em>Rest Day</em></td>";
                    }else{
                        $data .= "<td></td>";
                    }
                }

                //check kung may laman sa alter record table
                if(!empty($alter_record))
                {  
                    //kung may laman punch altered ang print sa remarks
                    $data .= "<td>
                    <input type='text' style='background-color:transparent; font-weight: bold; text-align:center;' value='Punch Altered' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' required disabled>
                    </td>";
                }
                else
                {
                    //kung wala, editable ang txtremarks para sa pag sasave
                    $data .= "<td>
                    <input type='text' style='background-color:transparent; font-weight: bold; text-align:center; display:none;' value='' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' required disabled>
                    </td>";
                }
            
                $data .= "</tr>";
                //end may record
            }
            else 
            {
                //absent
                $data .= "<input type='text' style='display:none' value='".$dates."]]]]' name='infos".$counter."' id='infos".$counter."' >"; //kinukuha nya yung date ng gusto nyang ialter at dahil absent sya null and prev time and out nya
                
                $data .= "<tr>";
                 //check nya kung may value na sa alter table kung meron na hindi na sya machecheck or else checkable 
                if(!empty($alter_record))
                {   
                    $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled/></td>';
                }
                else
                {
                    if($todayDate == $dates){
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required disabled></td>';
                    }else{
                        $data .= '<td><input type="checkbox" name="checkalt'.$counter.'" class="chkbox" id="checkalt'.$counter.'" data-add="'.$counter.'" required/></td>';
                    }
                }
                //end
                $data .= "<td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($dates))."</a>
                <br>
                <small>".date("l",strtotime($dates))."</small>
                </td>";
                
                //check nya yung kung may previous time in sya
                if($timein == "")
                {
                    if(!empty($alter_record))
                    { 
                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2; 
                        
                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required>
                        </td>";
                        }
                    }
                }
                else
                {
                    if(!empty($alter_record)){

                        $newtimein = $alter_record[0]->new_time_in;
                        $date = date("Y-m-d", strtotime("$newtimein")); 
                        $date2 = date("H:i", strtotime("$newtimein"));
                        $getnewtimein =  $date . 'T' . $date2; 

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='". $getnewtimein."' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }

                    }else{

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required disabled>
                            </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                            <input type='datetime-local' value='' class='form-control datetime' name='timein".$counter."' id='timein".$counter."'  required >
                            </td>";
                        }

                    }
                    
                }

                //check nya yung kung may previous time in sya
                if($timeout == "")
                {
                    if(!empty($alter_record))
                    {  
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='". $getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                    }
                    else
                    {
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>";
                        }
                    }
                }
                else
                {

                    if(!empty($alter_record)){
                        $newtimeout = $alter_record[0]->new_time_out;
                        $date = date("Y-m-d", strtotime("$newtimeout")); 
                        $date2 = date("H:i", strtotime("$newtimeout"));
                        $getnewtimeout =  $date . 'T' . $date2;

                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='".$getnewtimeout."' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }

                    }else{
                        if($todayDate == date("Y-m-d",strtotime($dates))){
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required disabled>
                        </td>";
                        }else{
                            $data .= "<td style='width:10%;'>
                        <input type='datetime-local' value='' class='form-control datetime' name='timeout".$counter."' id='timeout".$counter."' required>
                        </td>";
                        }
                    }
                }

                //total hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->total_hrs ."</td>";
                }
                else{
                    $data .= "<td>0.0</td>";
                }
                //check sa undertime hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->undertime ."</td>";
                }else{
                    $data .= "<td></td>";
                }

                //check sa late hours
                if(!empty($alter_record))
                {
                    $data .= "<td>". $alter_record[0]->late ."</td>";
                }else{
                    $data .= "<td></td>";
                }
                 
                //Check if Holidays matches the sched date 
                $restday = date("l",strtotime($dates));
                if(!empty($holiday_record))
                {
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ." (Rest Day)</em></td>";
                    }else{
                        $data .= "<td>". $holiday_record[0]->description ." - <em>". $holiday_record[0]->holiday_type ."</em></td>";
                    }
                }else{
                    if($restday == "Saturday" || $restday == "Sunday"){
                        $data .= "<td><em>Rest Day</em></td>";
                    }else{
                        $data .= "<td></td>";
                    }
                }


                if(!empty($alter_record))
                {
                    $data .= "<td><input type='text' style='background-color:transparent; font-weight: bold; text-align:center;' value='Punch Altered' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' required disabled></td>";
                }
                else{
                    $data .= "<td style='color: #dc3545;'><input style='background-color:transparent; font-weight: bold; text-align:center; color:#dc3545' type='text' value='Absent' class='form-control reason' name='txtremarks".$counter."' id='txtremarks".$counter."' required disabled></td>";
                }
                
                $data .= "</tr>";
                //end absent
            }
            $counter++;
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data .= "</tbody>
                </table>";

        
        echo $data;
    }

    //apply alteration old
        // public function apply_alteration(Request $request)
        // {
        //     $newlog_time_in = "";
        //     $error = array();
        //     $message = "";

        //     $current_date = new DateTime();
        //     $date_now = $current_date->format('Y-m-d');
            
        //     $cur_time_in = date('Y-m-d H:i:s', strtotime($request->cur_time_in));
        //     $cur_time_out = date('Y-m-d H:i:s', strtotime($request->cur_time_out));
        //     $new_time_in = date('Y-m-d H:i:s', strtotime($request->new_time_in));
        //     $new_time_out = date('Y-m-d H:i:s', strtotime($request->new_time_out));

        //     $newlog_time_in = date("Y-m-d", strtotime("$new_time_in"));
        
        //     if($request->new_time_in == "")
        //     {
        //         $messages = "Invalid new time in";
        //         $error[] = $messages;
        //     }
        //     if($request->new_time_out == "")
        //     {
        //         $messages = "Invalid new time out";
        //         $error[] = $messages;
        //     }
        //     if($request->txtReason == "") 
        //     {
        //         $messages = "Reason/Remarks required";
        //         $error[] = $messages;
        //     }
        //     if($newlog_time_in != $request->altered_date)
        //     {
        //         $messages = "New log time in is greater or less than the date you want to alter";
        //         $error[] = $messages;     
        //     }
        //     if($new_time_out <= $new_time_in)
        //     {
        //         $messages = "New log time out is greater than new log time in";
        //         $error[] = $messages;
        //     }
        //     if(empty($error))
        //     {
        //         if($request->cur_time_in == "" || $request->cur_time_out == "")
        //         {
        //             $apply_alteration = new AlterationRecordTable;
        //             $apply_alteration->company_id = $request->hidden_Id;
        //             $apply_alteration->sched_date = $request->altered_date;
        //             $apply_alteration->date_applied = $date_now;
        //             $apply_alteration->new_time_in = $new_time_in;
        //             $apply_alteration->new_time_out = $new_time_out;
        //             $apply_alteration->reason = $request->txtReason;
        //             $apply_alteration->lu_by = "Calvin Abueva";
        //             $apply_alteration->save();
        //             $message = "Alteration Applied!"; 
        //         }
        //         else
        //         {
        //             $apply_alteration = new AlterationRecordTable;
        //             $apply_alteration->company_id = $request->hidden_Id;
        //             $apply_alteration->sched_date = $request->altered_date;
        //             $apply_alteration->date_applied = $date_now;
        //             $apply_alteration->cur_time_in = $cur_time_in;
        //             $apply_alteration->cur_time_out =  $cur_time_out;
        //             $apply_alteration->new_time_in = $new_time_in;
        //             $apply_alteration->new_time_out = $new_time_out;
        //             $apply_alteration->reason = $request->txtReason;
        //             $apply_alteration->lu_by = "Calvin Abueva";
        //             $apply_alteration->save();
        //             $message = "Alteration Applied!";  
        //         }
        //     }
        //     else
        //     {

        //     }
        
        //     $output = array(
        //         'error'=>$error,
        //         'success'=>$message
        //     );

        //     echo json_encode($output);
        // }

    //apply alteration new

    public function apply_alteration(Request $request)
    {
        //Code for getting the current date of Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $todays_date = date("y-m-d");
            $today = strtotime($todays_date);
            $todayDate = date("Y-m-d", $today); 
        //Code for getting the current date of Asia/Manila  

        for($i = 1; $i <= 100; $i++)
        {
            $applyot = "false";
            $hour = 0.0;
            $total_hours = 0.0;
            $total_undertime = 0.0;
            $total_flexihrs = 0.0;
            $total_late = 0.0;


            if($request->input('checkalt' . $i))
            {
                if($request->input('checkalt' . $i) == "on")
                {
                    $current_date = new DateTime();
                    $date_now = $current_date->format('Y-m-d');

                    $altered_date = explode(']]', $request->input('infos' . $i));

                    $cur_time_in = date('Y-m-d H:i:s', strtotime($request->cur_time_in));
                    $cur_time_out = date('Y-m-d H:i:s', strtotime($request->cur_time_out));
                    $new_time_in = date('Y-m-d H:i:s', strtotime($request->input('timein'.$i)));
                    $new_time_out = date('Y-m-d H:i:s', strtotime($request->input('timeout'.$i)));

                    $datein = date('Y-m-d', strtotime($request->input('timein'.$i)));

                    // $d1= new DateTime($new_time_in); 
                    // $d2= new DateTime($new_time_out);
                    // $interval = $d1->diff($d2);
                    // $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                    
                    $sched_req = DB::connection('mysql3')->select("SELECT a.id, a.company_id, a.template_id, b.template, b.type, a.start_date, a.end_date, b.reg_in, b.reg_out, 
                        b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                        b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule_request AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                        WHERE a.deleted = '0' AND '".$datein."' BETWEEN a.start_date AND a.end_date AND a.company_id = '".auth()->user()->company_id."' ");

                    if(!empty($sched_req))
                    {
                        // IRREG
                        if($sched_req[0]->type == "Irregular Shift")
                        {
                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($sched_req[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout = new DateTime($timeout);
                                    
                                    $mon_out = $sched_req[0]->mon_out;
                                    $reg_monout = new DateTime($date_timeout . " " . $mon_out);
                                    
                                    if($day_timeout < $reg_monout)
                                    {
                                        $interval = $reg_monout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $mon_in = $sched_req[0]->mon_in;
                                    $reg_monin = new DateTime($date_timein. " ".$mon_in);

                                    if($day_timein > $reg_monin)
                                    {
                                        $interval = $reg_monin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_monin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $mon_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //TUESDAY
                            else if($day == "2")
                            {
                                if($sched_req[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $tue_out = $sched_req[0]->tue_out; //---6pm
                                    $reg_tueout = new DateTime($date_timeout . " " . $tue_out);

                                    if($day_timeout < $reg_tueout)
                                    {
                                        $interval = $reg_tueout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $tue_in = $sched_req[0]->tue_in;
                                    $reg_tuein = new DateTime($date_timein. " ". $tue_in);

                                    if($day_timein > $reg_tuein)
                                    {
                                        $interval = $reg_tuein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_tuein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $tue_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            else if($day == "3")
                            {
                                if($sched_req[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $wed_out = $sched_req[0]->wed_out; //---6pm
                                    $reg_wedout = new DateTime($datein . " " . $wed_out);

                                    if($day_timeout < $reg_wedout)
                                    {
                                        $interval = $reg_wedout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $wed_in = $sched_req[0]->wed_in;
                                    $reg_wedin = new DateTime($date_timein." ".$wed_in);

                                    if($day_timein > $reg_wedin)
                                    {
                                        $interval = $reg_wedin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_wedin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $wed_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THURSDAY
                            else if($day == "4")
                            {
                                if($sched_req[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $thu_out = $sched_req[0]->thu_out; //---6pm
                                    $reg_thuout = new DateTime($date_timeout . " " . $thu_out);

                                    if($day_timeout < $reg_thuout)
                                    {
                                        $interval = $reg_thuout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $thu_in = $sched_req[0]->thu_in;
                                    $reg_thuin = new DateTime($date_timein." ".$thu_in);

                                    if($day_timein > $reg_thuin)
                                    {
                                        $interval = $reg_thuin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_thuin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $thu_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRIDAY
                            else if($day == "5")
                            {
                                if($sched_req[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $fri_out = $sched_req[0]->fri_out; //---6pm
                                    $reg_friout = new DateTime($date_timeout . " " . $fri_out);

                                    if($day_timeout < $reg_friout)
                                    {
                                        $interval = $reg_friout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $fri_in = $sched_req[0]->fri_in;
                                    $reg_friin = new DateTime($date_timein." ".$fri_in);

                                    if($day_timein > $reg_friin)
                                    {
                                        $interval = $reg_friin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_friin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $fri_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SATURDAY
                            else if($day == "6")
                            {
                                if($sched_req[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $sat_out = $sched_req[0]->sat_out; //---6pm
                                    $reg_satout = new DateTime($date_timeout . " " . $sat_out);

                                    if($day_timeout < $reg_satout)
                                    {
                                        $interval = $reg_satout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $sat_in = $sched_req[0]->sat_in;
                                    $reg_satin = new DateTime($date_timein." ".$sat_in);

                                    if($day_timein > $reg_satin)
                                    {
                                        $interval = $reg_satin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_satin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $sat_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }else{
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUNDAY
                            else if($day == "7")
                            {
                                if($sched_req[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $sun_out = $sched_req[0]->sun_out; //---6pm
                                    $reg_sunout = new DateTime($date_timeout . " " . $sun_out);

                                    if($day_timeout < $reg_sunout)
                                    {
                                        $interval = $reg_sunout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $sun_in = $sched_req[0]->sun_in;
                                    $reg_sunin = new DateTime($date_timein." ".$sun_in);

                                    if($day_timein > $reg_sunin)
                                    {
                                        $interval = $reg_sunin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_sunin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $sun_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }
                        // END IRREG

                        // REGULAR
                        else if($sched_req[0]->type == "Regular Shift")
                        {
                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($sched_req[0]->mon == "1")
                                {

                                    $applyot = "true";
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i); 
                                    $day_timein =new DateTime($d_timein);

                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
                                    //kahombre rodrigo
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($sched_req[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($sched_req[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THURSDAY
                            if($day == "4")
                            {
                                if($sched_req[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRIDAY
                            if($day == "5")
                            {
                                if($sched_req[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SATURDAY
                            if($day == "6")
                            {
                                if($sched_req[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUNDAY
                            if($day == "7")
                            {
                                if($sched_req[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $sched_req[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $sched_req[0]->reg_out;                         
                                    $reg_timeout = new DateTime($date_timeout . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        } 
                        // END REGULAR

                        // FLEXI SHIFT
                        else if($sched_req[0]->type == "Flexi Shift")
                        {
                            $total_flexihrs = $sched_req[0]->flexihrs;   

                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($sched_req[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0; 
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($sched_req[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($sched_req[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THUR
                            if($day == "4")
                            {
                                if($sched_req[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRI
                            if($day == "5")
                            {
                                if($sched_req[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SAT
                            if($day == "6")
                            {
                                if($sched_req[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUN
                            if($day == "7")
                            {
                                if($sched_req[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }
                        // END FLEXI

                        // FREE SHIFT
                        else if($sched_req[0]->type == "Free Shift")
                        {
                            $total_flexihrs = $sched_req[0]->flexihrs;   

                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($sched_req[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0; 
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($sched_req[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($sched_req[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THUR
                            if($day == "4")
                            {
                                if($sched_req[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRI
                            if($day == "5")
                            {
                                if($sched_req[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SAT
                            if($day == "6")
                            {
                                if($sched_req[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUN
                            if($day == "7")
                            {
                                if($sched_req[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }
                        // END FREE
                        if($applyot == "true")
                        {
                            if($altered_date[1] == "" || $altered_date[2] == "")
                            {
                                $apply_alteration = new AlterationRecords;
                                $apply_alteration->company_id = auth()->user()->company_id;
                                $apply_alteration->sched_date = $altered_date[0];
                                $apply_alteration->date_applied = $date_now;
                                $apply_alteration->new_time_in = $new_time_in;
                                $apply_alteration->new_time_out = $new_time_out;
                                $apply_alteration->total_hrs = $hour;
                                $apply_alteration->undertime = $total_undertime;
                                $apply_alteration->late = $total_late;
                                $apply_alteration->reason = $request->input('txtReason_apply'); // $request->txtReason_apply;

                                // if($request->input('txtremarks'.$i) == "Punch Altered"){
                                //     $apply_alteration->alt_remarks = "Punch Altered";
                                // }else if($request->input('txtremarks'.$i) == "Absent"){
                                //     $apply_alteration->alt_remarks = "Absent";
                                // }else{
                                //     $apply_alteration->alt_remarks = "DTR";
                                // }

                                $apply_alteration->lu_by = auth()->user()->name; 
                                $apply_alteration->save();
                            }
                            else
                            {
                                $apply_alteration = new AlterationRecords;
                                $apply_alteration->company_id = auth()->user()->company_id;
                                $apply_alteration->sched_date = $altered_date[0];
                                $apply_alteration->date_applied = $date_now;
                                $apply_alteration->new_time_in = $new_time_in;
                                $apply_alteration->new_time_out = $new_time_out;
                                $apply_alteration->total_hrs = $hour;
                                $apply_alteration->undertime = $total_undertime;
                                $apply_alteration->late = $total_late;
                                $apply_alteration->reason = $request->input('txtReason_apply');

                                //$apply_alteration->reason = $request->input('txtremarks'.$i);
                                // if($request->input('txtremarks'.$i) == "Punch Altered"){
                                //     $apply_alteration->alt_remarks = "Punch Altered";
                                // }else if($request->input('txtremarks'.$i) == "Absent"){
                                //     $apply_alteration->alt_remarks = "Absent";
                                // }else{
                                //     $apply_alteration->alt_remarks = "DTR";
                                // }

                                $apply_alteration->lu_by = auth()->user()->name; 
                                $apply_alteration->save();
                            }
                        }else{
                            
                        }    
                    }
                    else
                    {
                        $emp_sched = DB::connection('mysql3')->select("SELECT a.id, a.company_id, a.template_id, b.template, b.type, b.reg_in, b.reg_out, b.mon_in, b.mon_out, b.mon, b.tue_in, b.tue_out, b.tue, b.wed_in, b.wed_out, b.wed, b.thu_in, b.thu_out, b.thu, b.fri_in, b.fri_out, b.fri, b.sat_in, b.sat_out, 
                            b.sat, b.sun_in, b.sun_out, b.sun, b.flexihrs FROM employee_schedule AS a LEFT JOIN schedule_template AS b ON a.template_id = b.ind 
                            WHERE a.deleted = '0' AND a.company_id = '".auth()->user()->company_id."'");
                            
                        // IRREG
                        if($emp_sched[0]->type == "Irregular Shift")
                        {
                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($emp_sched[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $mon_out = $emp_sched[0]->mon_out;
                                    $reg_monout = new DateTime($datein . " " . $mon_out);

                                    if($day_timeout < $reg_monout)
                                    {
                                        $interval = $reg_monout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $mon_in = $emp_sched[0]->mon_in;
                                    $reg_monin = new DateTime($date_timein. " ".$mon_in);

                                    if($day_timein > $reg_monin)
                                    {
                                        $interval = $reg_monin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_monin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $mon_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //TUESDAY
                            else if($day == "2")
                            {
                                if($emp_sched[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $tue_out = $emp_sched[0]->tue_out; //---6pm
                                    $reg_tueout = new DateTime($datein . " " . $tue_out);

                                    if($day_timeout < $reg_tueout)
                                    {
                                        $interval = $reg_tueout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $tue_in = $emp_sched[0]->tue_in;
                                    $reg_tuein = new DateTime($date_timein. " ". $tue_in);

                                    if($day_timein > $reg_tuein)
                                    {
                                        $interval = $reg_tuein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_tuein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $tue_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            else if($day == "3")
                            {
                                if($emp_sched[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $wed_out = $emp_sched[0]->wed_out; //---6pm
                                    $reg_wedout = new DateTime($datein . " " . $wed_out);

                                    if($day_timeout < $reg_wedout)
                                    {
                                        $interval = $reg_wedout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $wed_in = $emp_sched[0]->wed_in;
                                    $reg_wedin = new DateTime($date_timein." ".$wed_in);

                                    if($day_timein > $reg_wedin)
                                    {
                                        $interval = $reg_wedin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_wedin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $wed_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else
                                {
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THURSDAY
                            else if($day == "4")
                            {
                                if($emp_sched[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $thu_out = $emp_sched[0]->thu_out; //---6pm
                                    $reg_thuout = new DateTime($datein . " " . $thu_out);

                                    if($day_timeout < $reg_thuout)
                                    {
                                        $interval = $reg_thuout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $thu_in = $emp_sched[0]->thu_in;
                                    $reg_thuin = new DateTime($date_timein." ".$thu_in);

                                    if($day_timein > $reg_thuin)
                                    {
                                        $interval = $reg_thuin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_thuin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $thu_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    echo "w";
                                    $applyot = "false";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRIDAY
                            else if($day == "5")
                            {
                                if($emp_sched[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $fri_out = $emp_sched[0]->fri_out; //---6pm
                                    $reg_friout = new DateTime($datein . " " . $fri_out);

                                    if($day_timeout < $reg_friout)
                                    {
                                        $interval = $reg_friout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $fri_in = $emp_sched[0]->fri_in;
                                    $reg_friin = new DateTime($date_timein." ".$fri_in);

                                    if($day_timein > $reg_friin)
                                    {
                                        $interval = $reg_friin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_friin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $fri_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SATURDAY
                            else if($day == "6")
                            {
                                if($emp_sched[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $sat_out = $emp_sched[0]->sat_out; //---6pm
                                    $reg_satout = new DateTime($datein . " " . $sat_out);

                                    if($day_timeout < $reg_satout)
                                    {
                                        $interval = $reg_satout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $sat_in = $emp_sched[0]->sat_in;
                                    $reg_satin = new DateTime($date_timein." ".$sat_in);

                                    if($day_timein > $reg_satin)
                                    {
                                        $interval = $reg_satin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_satin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $sat_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }else{
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUNDAY
                            else if($day == "7")
                            {
                                if($emp_sched[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    //UNDERTIME
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    $day_timeout =new DateTime($timeout);

                                    $sun_out = $emp_sched[0]->sun_out; //---6pm
                                    $reg_sunout = new DateTime($datein . " " . $sun_out);

                                    if($day_timeout < $reg_sunout)
                                    {
                                        $interval = $reg_sunout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME

                                    //LATE
                                    $timein = $request->input('timein'.$i);
                                    $date_timein = date('Y-m-d', strtotime($timein)); //get the date of day time in to bind with the regular timein
                                    $day_timein =new DateTime($timein);
                                    
                                    $sun_in = $emp_sched[0]->sun_in;
                                    $reg_sunin = new DateTime($date_timein." ".$sun_in);

                                    if($day_timein > $reg_sunin)
                                    {
                                        $interval = $reg_sunin->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_sunin)
                                    {
                                        $d1= new DateTime($date_timein . " " . $sun_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                }
                                else{
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }
                        // END IRREG

                        // REGULAR
                        else if($emp_sched[0]->type == "Regular Shift")
                        {
                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($emp_sched[0]->mon == "1")
                                {
                                    $applyot = "true";
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i); 
                                    $day_timein =new DateTime($d_timein);

                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE

                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($emp_sched[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($emp_sched[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THURSDAY
                            if($day == "4")
                            {
                                if($emp_sched[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRIDAY
                            if($day == "5")
                            {
                                if($emp_sched[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SATURDAY
                            if($day == "6")
                            {
                                if($emp_sched[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout
                                    
                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w";
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUNDAY
                            if($day == "7")
                            {
                                if($emp_sched[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $timeout = $request->input('timeout'.$i);
                                    $date_timeout = date('Y-m-d', strtotime($timeout)); //get the date of day time out to bind with the regular timeout

                                    $d_timein = $request->input('timein'.$i);
                                    $day_timein =new DateTime($d_timein);
                                    $date_timein = date('Y-m-d', strtotime($d_timein)); //get the date of day time in to bind with the regular timein
                                    $r_timein = $emp_sched[0]->reg_in;
                                    $reg_timein = new DateTime($date_timein." ".$r_timein);

                                    $r_timeout = $emp_sched[0]->reg_out;                         
                                    $reg_timeout = new DateTime($datein . " " . $r_timeout);
    
                                    $d_timeout = $request->input('timeout'.$i);
                                    $day_timeout =new DateTime($d_timeout);
    
                                    //UNDERTIME
                                    if($day_timeout < $reg_timeout)
                                    {
                                        $interval = $reg_timeout->diff($day_timeout);
                                        $total_undertime = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_undertime = 0.0;
                                    }
                                    //END UNDERTIME
    
                                    //LATE
                                    if($day_timein > $reg_timein)
                                    {
                                        $interval = $reg_timein->diff($day_timein);
                                        $total_late = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else{
                                        $total_late = 0.0;
                                    }
                                    //END LATE
                                    
                                    //COMPUTE TOTAL HOURS
                                    $date_timein = date("Y-m-d", strtotime($request->input('timein'.$i)));//get the date he want to alter and bind to reg timein    
                                    if($day_timein < $reg_timein)
                                    {
                                        $d1= new DateTime($date_timein . " " . $r_timein); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    }
                                    else
                                    {
                                        $d1= new DateTime($new_time_in); 
                                        $d2= new DateTime($new_time_out);
                                        $interval = $d1->diff($d2);
                                        $hour = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);           
                                    }  
                                    // END TOTAL HOURS
                                }
                                else
                                {
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        } 
                        // END REGULAR

                        // FLEXI SHIFT
                        else if($emp_sched[0]->type == "Flexi Shift")
                        {
                            $total_flexihrs = $emp_sched[0]->flexihrs;   

                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($emp_sched[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0; 
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($emp_sched[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($emp_sched[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THUR
                            if($day == "4")
                            {
                                if($emp_sched[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRI
                            if($day == "5")
                            {
                                if($emp_sched[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SAT
                            if($day == "6")
                            {
                                if($emp_sched[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUN
                            if($day == "7")
                            {
                                if($emp_sched[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }
                        // END FLEXI

                        // FREE SHIFT
                        else if($emp_sched[0]->type == "Free Shift")
                        {
                            $total_flexihrs = $emp_sched[0]->flexihrs;   

                            $day = date("N", strtotime($request->input('timein'.$i))); //converts the date into day
                            //MONDAY
                            if($day == "1")
                            {
                                if($emp_sched[0]->mon == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0; 
                                }
                            }
                            //TUESDAY
                            if($day == "2")
                            {
                                if($emp_sched[0]->tue == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //WEDNESDAY
                            if($day == "3")
                            {
                                if($emp_sched[0]->wed == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //THUR
                            if($day == "4")
                            {
                                if($emp_sched[0]->thu == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //FRI
                            if($day == "5")
                            {
                                if($emp_sched[0]->fri == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SAT
                            if($day == "6")
                            {
                                if($emp_sched[0]->sat == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                            //SUN
                            if($day == "7")
                            {
                                if($emp_sched[0]->sun == "1")
                                {
                                    $applyot = "true"; 
                                    $d1= new DateTime($new_time_in); 
                                    $d2= new DateTime($new_time_out);
                                    $interval = $d1->diff($d2);
                                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);
                                    if($total_hours <= $total_flexihrs)
                                    {
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
                                    $applyot = "false"; 
                                    echo "w"; 
                                    $hour = 0.0;
                                    $total_hours = 0.0;
                                    $total_undertime = 0.0;
                                    $total_flexihrs = 0.0;
                                    $total_late = 0.0;
                                }
                            }
                        }

                        // END FREE
                        if($applyot == "true")
                        {
                            if($altered_date[1] == "" || $altered_date[2] == "")
                            {
                                $apply_alteration = new AlterationRecords;
                                $apply_alteration->company_id = auth()->user()->company_id;
                                $apply_alteration->sched_date = $altered_date[0];
                                $apply_alteration->date_applied = $date_now;
                                $apply_alteration->new_time_in = $new_time_in;
                                $apply_alteration->new_time_out = $new_time_out;
                                $apply_alteration->total_hrs = $hour;
                                $apply_alteration->undertime = $total_undertime;
                                $apply_alteration->late = $total_late;
                                $apply_alteration->reason = $request->input('txtReason_apply');

                                // if($request->input('txtremarks'.$i) == "Punch Altered"){
                                //     $apply_alteration->alt_remarks = "Punch Altered";
                                // }else if($request->input('txtremarks'.$i) == "Absent"){
                                //     $apply_alteration->alt_remarks = "Absent";
                                // }else{
                                //     $apply_alteration->alt_remarks = "DTR";
                                // }

                                //$apply_alteration->reason = $request->input('txtremarks'.$i);
                                $apply_alteration->lu_by = auth()->user()->name; 
                                $apply_alteration->save();
                            }
                            else
                            {
                                $apply_alteration = new AlterationRecords;
                                $apply_alteration->company_id = auth()->user()->company_id;
                                $apply_alteration->sched_date = $altered_date[0];
                                $apply_alteration->date_applied = $date_now;
                                $apply_alteration->new_time_in = $new_time_in;
                                $apply_alteration->new_time_out = $new_time_out;
                                $apply_alteration->total_hrs = $hour;
                                $apply_alteration->undertime = $total_undertime;
                                $apply_alteration->late = $total_late;
                                $apply_alteration->reason = $request->input('txtReason_apply');

                                // if($request->input('txtremarks'.$i) == "Punch Altered"){
                                //     $apply_alteration->alt_remarks = "Punch Altered";
                                // }else if($request->input('txtremarks'.$i) == "Absent"){
                                //     $apply_alteration->alt_remarks = "Absent";
                                // }else{
                                //     $apply_alteration->alt_remarks = "DTR";
                                // }

                                //$apply_alteration->reason = $request->input('txtremarks'.$i);
                                $apply_alteration->lu_by = auth()->user()->name; 
                                $apply_alteration->save();
                            }
                        }   
                    }

                    // if($altered_date[1] == "" || $altered_date[2] == "")
                    // {
                    //     $apply_alteration = new AlterationRecords;
                    //     $apply_alteration->company_id = $request->input('hidden_ID');
                    //     $apply_alteration->sched_date = $altered_date[0];
                    //     $apply_alteration->date_applied = $date_now;
                    //     $apply_alteration->new_time_in = $new_time_in;
                    //     $apply_alteration->new_time_out = $new_time_out;
                    //     $apply_alteration->total_hrs = $hour;
                    //     $apply_alteration->reason = $request->input('txtremarks' . $i);
                    //     $apply_alteration->lu_by = auth()->user()->name; 
                    //     $apply_alteration->save();
                    // }
                    // else
                    // {
                    //     $apply_alteration = new AlterationRecordTable;
                    //     $apply_alteration->company_id = $request->input('hidden_ID');
                    //     $apply_alteration->sched_date = $altered_date[0];
                    //     $apply_alteration->date_applied = $date_now;
                    //     $apply_alteration->cur_time_in = $altered_date[1];
                    //     $apply_alteration->cur_time_out =  $altered_date[2];
                    //     $apply_alteration->new_time_in = $new_time_in;
                    //     $apply_alteration->new_time_out = $new_time_out;
                    //     $apply_alteration->total_hrs = $hour;
                    //     $apply_alteration->reason = $request->input('txtremarks'.$i);
                    //     $apply_alteration->lu_by = auth()->user()->name; 
                    //     $apply_alteration->save();
                    // }
                }
            }
        } 
    }

}

