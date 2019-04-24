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
                                    <th style="border-top:0px;"></th>
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
}
