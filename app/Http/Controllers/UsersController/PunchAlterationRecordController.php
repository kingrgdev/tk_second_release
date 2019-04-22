<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AlterationRecords;
use Validator;
use DB;
use DateTime;
class PunchAlterationRecordController extends Controller
{
    public function index()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' ");
        return view('modules/usersmodule/timerecords/punch_alteration_records')->with('alteration_record', $alteration_record);
    }

    //refresh table
    public function refresh_table()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //cancel alteration
    public function cancel_alteration(Request $request)
    {
        // $id_to_cancel = $request->id_to_cancel;
        // $data = DB::connection('mysql')->select("UPDATE alteration_record_tables SET deleted = '1', lu_by='Calvin Abueva' ");
        $cancel_alteration = AlterationRecords::find($request->id_to_cancel);
        $cancel_alteration->status = 'CANCELLED';
        $cancel_alteration->lu_by = "Calvin Abueva";
        $cancel_alteration->save();
        $message = "Alteration Cancelled Succesfully!"; 

        echo json_encode($message);
    }

    //search by all
    public function search_by_all()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by alteration date
    public function search_by_alterationdate(Request $request)
    {
        $start_date_alteration = $request->start_date_alteration;
        $end_date_alteration = $request->end_date_alteration;
        
        $start_date = date("Y-m-d", strtotime("$start_date_alteration"));
        $end_date = date("Y-m-d", strtotime("$end_date_alteration"));

        $data = '<div class="table-responsive">
            <table id="table_punch_alteration_record" name="example" class="table table-hover" style="width:100%">
                <col>
                <colgroup span="2"></colgroup>
                <colgroup span="2"></colgroup>
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">Date Applied</th>
                        <th colspan="2" scope="colgroup" class="text-center">Previous Log</th>                      
                        <th colspan="2" scope="colgroup" class="text-center">Applied Alteration</th>
                        <th rowspan="2" class="text-center">Reason</th>
                        <th colspan="2" scope="colgroup" class="text-center">Approval History</th>
                        <th rowspan="2" style="text-align:center;">Remarks</th>                      
                        <tr>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Level 1</th>
                            <th scope="col">Level 2</th>
                        </tr>                       
                    </tr>          
                </thead>
                <tbody>';
        
        while($start_date <= $end_date)
        {
            $result_data = DB::connection('mysql')->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND sched_date = '$start_date' ");

            if(!empty($result_data))
            {
                $data .= "<tr>";
                if($result_data[0]->date_applied == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y",strtotime($result_data[0]->date_applied)) .'<br><small>'.date("l",strtotime($result_data[0]->date_applied)).'</small></td>';
                }
                if($result_data[0]->cur_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->cur_time_in)) .'</td>';
                }
                if($result_data[0]->cur_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->cur_time_out)) .'</td>';
                }
                if($result_data[0]->new_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->new_time_in)) .'</td>';
                }
                if($result_data[0]->new_time_out == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->new_time_out)) .'</td>';
                }
                $data .= "<td style='text-align:center;'>". $result_data[0]->reason ."</td>";
                // $data .= "<td>". $result_data[0]->level1name ."</td>";
                // $data .= "<td>". $result_data[0]->level2name ."</td>";
                // $data .= '<td>
                //             <button type="button" class="btn btn-danger btn_Cancel" data-add="{{$record->id}}">Cancel Application</button>
                //         </td>';

                if($result_data[0]->status == "CANCELLED")
                {
                    $data .= '<td style=""></td>
                    <td style=""></td>
                    <td colspan ="3" style="color:#dc3545; text-align:center;"><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>
                            ';
                }
                else
                {
                    if($result_data[0]->approved_1 == "0")
                    {
                        $data .= "<td>".$result_data[0]->status."</td>";
                    }
                    else
                    {
                        $data .= "<td>".$result_data[0]->level1name."</td>"; 
                    }
                        
                    if($result_data[0]->approved_2 == "0")
                    {
                        $data .= "<td>".$result_data[0]->status."</td>";
                    }
                    else
                    {
                        $data .= "<td>".$result_data[0]->level2name."</td>"; 
                    }
                    
                    if($result_data[0]->status == "APPROVED")
                    {
                        $data .= "<td style='color:28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                    }
                    else
                    {
                        $data .= '<td style="text-align:center;">
                                    <button type="button" class="btn btn-sm button red btn_Cancel" data-add="{{$record->id}}">Cancel Application</button>
                                </td>';
                    }
                }
            }
            else 
            {
               
            }
            $data .= "</tr>";
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data .= "</tbody>
                </table>";

        echo $data;
    }

    //search by date applied
    public function search_by_dateapplied(Request $request)
    {
        $start_date_applied = $request->start_date_applied;
        $end_date_applied = $request->end_date_applied;
        
        $start_date = date("Y-m-d", strtotime("$start_date_applied"));
        $end_date = date("Y-m-d", strtotime("$end_date_applied"));

        $data = '<div class="table-responsive">
            <table id="table_punch_alteration_record" name="example" class="table table-hover" style="width:100%">
                <col>
                <colgroup span="2"></colgroup>
                <colgroup span="2"></colgroup>
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">Date Applied</th>
                        <th colspan="2" scope="colgroup" class="text-center">Previous Log</th>                      
                        <th colspan="2" scope="colgroup" class="text-center">Applied Alteration</th>
                        <th rowspan="2" class="text-center">Reason</th>
                        <th colspan="2" scope="colgroup" class="text-center">Approval History</th>
                        <th rowspan="2" style="text-align:center;">Remarks</th>                      
                        <tr>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Level 1</th>
                            <th scope="col">Level 2</th>
                        </tr>                       
                    </tr>          
                </thead>
                <tbody>';
        
        while($start_date <= $end_date)
        {
            $result_data = DB::connection('mysql')->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND date_applied = '$start_date' ");

            if(!empty($result_data))
            {
                $data .= "<tr>";
                if($result_data[0]->date_applied == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y",strtotime($result_data[0]->date_applied)) .'<br><small>'.date("l",strtotime($result_data[0]->date_applied)).'</small></td>';
                }
                if($result_data[0]->cur_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->cur_time_in)) .'</td>';
                }
                if($result_data[0]->cur_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->cur_time_out)) .'</td>';
                }
                if($result_data[0]->new_time_in == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->new_time_in)) .'</td>';
                }
                if($result_data[0]->new_time_out == ""){
                    $data .= '<td></td>';
                }else{
                    $data .= '<td>' . date("F j Y \- h:i A",strtotime($result_data[0]->new_time_out)) .'</td>';
                }
                $data .= "<td style='text-align:center;'>". $result_data[0]->reason ."</td>";
                // $data .= "<td>". $result_data[0]->level1name ."</td>";
                // $data .= "<td>". $result_data[0]->level2name ."</td>";
                // $data .= '<td>
                //             <button type="button" class="btn btn-danger btn_Cancel" data-add="{{$record->id}}">Cancel Application</button>
                //         </td>';

                if($result_data[0]->status == "CANCELLED")
                {
                    $data .= '<td style=""></td>
                    <td style=""></td>
                    <td colspan ="3" style="color:#dc3545; text-align:center;"><i class="icon-right fa fa-times-circle"></i><b>CANCELLED</b></td>
                            ';
                }
                else
                {
                    if($result_data[0]->approved_1 == "0")
                    {
                        $data .= "<td>".$result_data[0]->status."</td>";
                    }
                    else
                    {
                        $data .= "<td>".$result_data[0]->level1name."</td>"; 
                    }
                        
                    if($result_data[0]->approved_2 == "0")
                    {
                        $data .= "<td>".$result_data[0]->status."</td>";
                    }
                    else
                    {
                        $data .= "<td>".$result_data[0]->level2name."</td>"; 
                    }
                    
                    if($result_data[0]->status == "APPROVED")
                    {
                        $data .= "<td style='color:28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                    }
                    else
                    {
                        $data .= '<td style="text-align:center;">
                                    <button type="button" class="btn btn-sm button red btn_Cancel" data-add="{{$record->id}}">Cancel Application</button>
                                </td>';
                    }
                }
            }
            else 
            {
               
            }
            $data .= "</tr>";
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data .= "</tbody>
                </table>";

        echo $data;
    }

    //search by all_status
    public function search_by_allstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by pending_status
    public function search_by_pendingstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND deleted = '0' AND status = 'PENDING' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by approved_status
    public function search_by_approvedstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND deleted = '0' AND status = 'APPROVED' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by rejected_status
    public function search_by_rejectedstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND deleted = '0' AND status = 'REJECTED' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by cancelled_status
    public function search_by_cancelledstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND deleted = '0' AND status = 'CANCELLED' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //search by retracted_status
    public function search_by_retractedstatus()
    {
        $alteration_record = DB::connection("mysql")->select("SELECT * FROM view_punch_alteration_records WHERE company_id = '".auth()->user()->company_id."' AND deleted = '0' AND status = 'RETRACTED' ");
        return view('modules/usersmodule/timerecords/table/punch_alter_table')->with('alteration_record', $alteration_record);
    }

    //Punch History View
    public function punch_history_view()
    {
        return view('modules.usersmodule.timerecords.punch_history');
    }

    //Punch History List
    public function show_history_list(Request $request){

        $dates = "";
        $current_date = new DateTime();
        $data = "";
        $data .= '<table id="table_punch_history" name="table_punch_history" class="table table-hover" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th style="text-align:center;">Total Hours</th>
                                <th style="text-align:center;">Overtime</th>
                                <th style="text-align:center;">Considered Hours</th>
                                <th>Holiday</th>
                            </tr>
                        </thead>
                    <tbody>';
        $counter = 1;
        for($a = 0; $a <= 100; $a++)
        {
            $dates = $current_date->format('Y-m-d');    

            $view_alteration_records = DB::connection('mysql')->select("SELECT ar.date_applied, ar.sched_date AS sched_date_alter, ar.new_time_in, ar.new_time_out, ar.total_hrs, ot.total_hrs AS total_hrs_ot, 
        ot.sched_date AS sched_date_overtime FROM view_alteration_records AS ar LEFT JOIN overtime_records AS ot ON ar.company_id = ot.company_id WHERE ar.company_id = '".auth()->user()->company_id."' AND ar.sched_date = '$dates' AND (ar.status != 'CANCELLED' AND ar.status != 'REJECTED') ORDER BY ar.sched_date DESC");

            $holiday_record = DB::connection('mysql2')->select("SELECT * FROM holidays WHERE holiday_date = '$dates'");

            $view_employee_schedule = DB::connection('mysql3')->select("SELECT * FROM view_employee_schedule WHERE company_id = '".auth()->user()->company_id."'");
            
            $data .= "<tr>";
            $data .= "
                    <td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($dates))."</a>
                    <br>
                    <small>".date("l",strtotime($dates))."</small>
                    </td>
                    ";

                    
            if(!empty($view_alteration_records))
            {
                $time_in = date("Y-m-d H:i:s",strtotime($view_alteration_records[0]->new_time_in));
                                 
                $time_out = date("Y-m-d H:i:s",strtotime($view_alteration_records[0]->new_time_out));
                                
                $sched_date_alter = date("Y-m-d",strtotime($view_alteration_records[0]->sched_date_alter));
                
                $sched_date_overtime = date("Y-m-d",strtotime($view_alteration_records[0]->sched_date_overtime));
                
                $dayPresent = date("l",strtotime($view_alteration_records[0]->sched_date_alter));

                //Time In
                    $data .= "<td>
                            " . date("F j Y",strtotime($view_alteration_records[0]->new_time_in)) . "
                            <br>
                            " . date("h:i A",strtotime($view_alteration_records[0]->new_time_in)) . "
                            </td>";
                //Time In

                //Time Out
                    $data .= "<td>
                            " . date("F j Y",strtotime($view_alteration_records[0]->new_time_out)) . "
                            <br>
                            " . date("h:i A",strtotime($view_alteration_records[0]->new_time_out)) . "
                            </td>";
                //Time Out

                //Total Hours
                    $data .= "<td style='text-align:center;'>".$view_alteration_records[0]->total_hrs."</td>";
                //Total Hours

                //Overtime
                    if($sched_date_alter == $sched_date_overtime){
                        $data .= "<td style='text-align:center;'>".$view_alteration_records[0]->total_hrs_ot."</td>";
                    }else{
                        $data .= "<td style='text-align:center;'>--.--</td>";
                    }
                //Overtime
                
                //Considered Hours
                    $total_hours = $view_alteration_records[0]->total_hrs;
                    $total_overtime_hours = $view_alteration_records[0]->total_hrs_ot;

                    if($sched_date_alter == $sched_date_overtime){
                        $data .= "<td style='text-align:center;'>".$total_hours."</td>";
                    }else{
                        if(floor($total_hours) >= 11){
                            $compute_hours = $total_hours - 10;
                            $whole = floor($compute_hours);     // First part
                            $decimal_part = $compute_hours - $whole; // Second part
                            $result = 1 . $decimal_part;

                            if(strval($result) == "10"){
                                $result_hours = "10.00";
                            }else{
                                $result_hours = 1 . $decimal_part;
                            }

                            $data .= "<td style='text-align:center;'>".$result_hours."</td>";

                        }else{
                            $data .= "<td style='text-align:center;'>".$total_hours."</td>";
                        }
                    }
                //Considered Hours
            }
            else
            {
                $data .= "<td></td>";
                $data .= "<td></td>";
                $data .= "<td style='text-align:center;'>--.--</td>";
                $data .= "<td style='text-align:center;'>--.--</td>";
                $data .= "<td style='text-align:center;'>--.--</td>";
            }

            //Holiday
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
            //Holiday
            
            $data .= "</tr>";
            
        $counter++;
        $current_date->modify('-1 day'); 
        }
        $data .= '</tbody></table>';
        echo $data;
    }

    //Punch History Filter
    public function show_history_filter(Request $request){
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

        $data = '<table id="table_punch_history" name="table_punch_history" class="table table-hover" >
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th style="text-align:center;">Total Hours</th>
                            <th style="text-align:center;">Overtime</th>
                            <th style="text-align:center;">Considered Hours</th>
                            <th>Holiday</th>
                        </tr>
                    </thead>
                <tbody>';

        $counter = 1;

        while($start_date <= $end_date)
        {
            $dates = date("Y-m-d", strtotime("$start_date"));

            $view_alteration_records = DB::connection('mysql')->select("SELECT ar.date_applied, ar.sched_date AS sched_date_alter, ar.new_time_in, ar.new_time_out, ar.total_hrs, ot.total_hrs AS total_hrs_ot, 
            ot.sched_date AS sched_date_overtime FROM view_alteration_records AS ar LEFT JOIN overtime_records AS ot ON ar.company_id = ot.company_id WHERE ar.company_id = '".auth()->user()->company_id."' AND ar.sched_date = '$dates' AND (ar.status != 'CANCELLED' AND ar.status != 'REJECTED') ORDER BY ar.sched_date DESC");
    
            $holiday_record = DB::connection('mysql2')->select("SELECT * FROM holidays WHERE holiday_date = '$dates'");
    
            $view_employee_schedule = DB::connection('mysql3')->select("SELECT * FROM view_employee_schedule WHERE company_id = '".auth()->user()->company_id."'");
            
            $data .= "<tr>";
            $data .= "
                    <td><a id='dateApplied".$counter."'>".date("F j Y",strtotime($dates))."</a>
                    <br>
                    <small>".date("l",strtotime($dates))."</small>
                    </td>
                    ";

                    if(!empty($view_alteration_records))
                    {
                        $time_in = date("Y-m-d H:i:s",strtotime($view_alteration_records[0]->new_time_in));
                                         
                        $time_out = date("Y-m-d H:i:s",strtotime($view_alteration_records[0]->new_time_out));
                                        
                        $sched_date_alter = date("Y-m-d",strtotime($view_alteration_records[0]->sched_date_alter));
                        
                        $sched_date_overtime = date("Y-m-d",strtotime($view_alteration_records[0]->sched_date_overtime));
                        
                        $dayPresent = date("l",strtotime($view_alteration_records[0]->sched_date_alter));
        
                        //Time In
                            $data .= "<td>
                                    " . date("F j Y",strtotime($view_alteration_records[0]->new_time_in)) . "
                                    <br>
                                    " . date("h:i A",strtotime($view_alteration_records[0]->new_time_in)) . "
                                    </td>";
                        //Time In
        
                        //Time Out
                            $data .= "<td>
                                    " . date("F j Y",strtotime($view_alteration_records[0]->new_time_out)) . "
                                    <br>
                                    " . date("h:i A",strtotime($view_alteration_records[0]->new_time_out)) . "
                                    </td>";
                        //Time Out
        
                        //Total Hours
                            $data .= "<td style='text-align:center;'>".$view_alteration_records[0]->total_hrs."</td>";
                        //Total Hours
        
                        //Overtime
                            if($sched_date_alter == $sched_date_overtime){
                                $data .= "<td style='text-align:center;'>".$view_alteration_records[0]->total_hrs_ot."</td>";
                            }else{
                                $data .= "<td style='text-align:center;'>--.--</td>";
                            }
                        //Overtime
                        
                        //Considered Hours
                            $total_hours = $view_alteration_records[0]->total_hrs;
                            $total_overtime_hours = $view_alteration_records[0]->total_hrs_ot;
        
                            if($sched_date_alter == $sched_date_overtime){
                                $data .= "<td style='text-align:center;'>".$total_hours."</td>";
                            }else{
                                if(floor($total_hours) >= 11){
                                    $compute_hours = $total_hours - 10;
                                    $whole = floor($compute_hours);     // First part
                                    $decimal_part = $compute_hours - $whole; // Second part
                                    $result = 1 . $decimal_part;
        
                                    if(strval($result) == "10"){
                                        $result_hours = "10.00";
                                    }else{
                                        $result_hours = 1 . $decimal_part;
                                    }
        
                                    $data .= "<td style='text-align:center;'>".$result_hours."</td>";
        
                                }else{
                                    $data .= "<td style='text-align:center;'>".$total_hours."</td>";
                                }
                            }
                        //Considered Hours
                    }
                    else
                    {
                        $data .= "<td></td>";
                        $data .= "<td></td>";
                        $data .= "<td style='text-align:center;'>--.--</td>";
                        $data .= "<td style='text-align:center;'>--.--</td>";
                        $data .= "<td style='text-align:center;'>--.--</td>";
                    }
        
                    //Holiday
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
                    //Holiday
                    
                    $data .= "</tr>";

            $counter++;
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data .= "</tbody>
                </table>";

        echo $data;
    }
}
