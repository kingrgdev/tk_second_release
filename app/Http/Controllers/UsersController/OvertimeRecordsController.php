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
    public function index()
    {
        return view('modules.usersmodule.overtimerecords.overtimerecords');
    }
    public function print_overtime_now()
    {
        $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."'");
        $data = "";
        $data .= '<div id="divOvertimeRecord"class="table-responsive">
                    <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-hover" style="width:100%">
                        <col>
                        <colgroup span="2"></colgroup>
                        <colgroup span="2"></colgroup>
                        <thead>
                            <tr class="header" style="background:#f7f7f7;">
                                <th colspan="9" class="text-center">OVERTIME RECORDS</th>
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
                    $data .= "<td></td>";
                    $data .= "<td></td>";
                    if($field->status == "APPROVED")
                    {
                        $data .= "<td style='color:#28a745; text-align:center;'><i class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>";
                    }
                    else if ($field->status == "CANCELLED"){
                        $data .= "<td style='color:#dc3545;'><i class='icon-right fa fa-times-circle'></i><b>CANCELLED</b></td>";
                    }
                    else if ($field->status == "PENDING")
                    {
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
        //Code for getting the current date of Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $todays_date = date("y-m-d");
            $today = strtotime($todays_date);
            $todayDate = date("Y-m-d", $today); 
        //Code for getting the current date of Asia/Manila
        
        $apply_overtime = new OvertimeRecords;
        $apply_overtime->company_id = auth()->user()->company_id;
        $apply_overtime->date_applied = date("Y-m-d",strtotime($todayDate));
        $apply_overtime->sched_date = date("Y-m-d",strtotime($request->schedDate));
        $apply_overtime->shift_applied = $request->cmbShift;
        $apply_overtime->date_timein = date("Y-m-d h:i:s",strtotime($request->timeIn));
        $apply_overtime->date_timeout = date("Y-m-d h:i:s",strtotime($request->timeOut));
        $apply_overtime->total_hrs = 12;
        $apply_overtime->reason = $request->txtReason;
        $apply_overtime->lu_by = "Admin"; 
        $apply_overtime->save();
        $message = "Overtime Applied Succesfully!"; 
        echo json_encode($message);
    }
    public function filter_dates(Request $request){
        //Code for getting the current date of Asia/Manila
            date_default_timezone_set('Asia/Manila');
            $todays_date = date("y-m-d");
            $today = strtotime($todays_date);
            $todayDate = date("Y-m-d", $today); 
        //Code for getting the current date of Asia/Manila
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $start_date = date("Y-m-d", strtotime("$start_date"));
        $end_date = date("Y-m-d", strtotime("$end_date"));

        $message = $start_date."-".$end_date;
        echo json_encode($message);
        
        // $data = '<div id="divOvertimeRecord"class="table-responsive">
        //             <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-hover" style="width:100%">
        //                 <col>
        //                 <colgroup span="2"></colgroup>
        //                 <colgroup span="2"></colgroup>
        //                 <thead>
        //                     <tr class="header" style="background:#f7f7f7;">
        //                         <th colspan="9" class="text-center">OVERTIME RECORDS</th>
        //                     </tr>
        //                     <tr>
        //                         <th rowspan="2">Date Applied</th>
        //                         <th rowspan="2">Applied Time In</th>
        //                         <th rowspan="2">Applied Time Out</th>
        //                         <th rowspan="2">Shift Type</th>
        //                         <th rowspan="2" >Reason</th>
        //                         <th rowspan="2" >Total Hours</th>
        //                         <th colspan="2" scope="colgroup" style="">Approval History</th>
        //                         <tr>
                                    
        //                             <th scope="col">Level 1</th>
        //                             <th scope="col" >Level 2</th>
        //                             <th style="border-top:0px;">
                                        
        //                             </th>
        //                         </tr>
        //                     </tr>          
        //                 </thead>
        //         <tbody>';

        // $counter = 1;

        // while($start_date <= $end_date)
        // {
        //     $dates = date("Y-m-d", strtotime("$start_date"));

        //     $view_overtime_records = DB::connection('mysql')->select("SELECT * FROM view_overtime_records WHERE company_id = '".auth()->user()->company_id."' AND sched = '$dates'");

        //     $data .= "<tr>";

        //             if(!empty($view_alteration_records))
        //             {
        //                 $data .= "<td>".$view_overtime_records[0]->date_applied."</td>";
        //                 $data .= "<td>".$view_overtime_records[0]->date_timein."</td>";
        //                 $data .= "<td>".$view_overtime_records[0]->date_timeout."</td>";
        //                 $data .= "<td>".$view_overtime_records[0]->shift_applied."</td>";
        //                 $data .= "<td>".$view_overtime_records[0]->reason."</td>";
        //                 $data .= "<td>".$view_overtime_records[0]->total_hrs."</td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //             }
        //             else
        //             {
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //                 $data .= "<td></td>";
        //             }
        
                    
                    
        //     $data .= "</tr>";
        //     $counter++;
        // }

        // $data .= "</tbody>
        //         </table>";

        // echo $data;
    }
}
