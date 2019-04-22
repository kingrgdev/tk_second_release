<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecords;
use App\Models\DateTimeRecords;
use DB;
use Excel;
use DateTime;
use PHPExcel_Cell;
use Session;


class ImportBiometrics extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.importbiometrics.importbiometrics');
    }

    public function uploadBiometrics(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        //get excel file
        $excel_path = $request->file('upfiles')->getRealPath();
        $excel_data = Excel::load($excel_path)->get();

        //variable holder in database
        $date_time_in = "";
        $date_time_out = "";

        foreach($excel_data as $excel_value){
            $ac_no = "ac_no.";
            $biometrics_id = $excel_value->$ac_no;
            // $emp_name = $excel_value->name;
            $date_time = date("Y-m-d H:i:s", strtotime($excel_value->time));
            $sched_date = date("Y-m-d", strtotime($excel_value->time));
            $state = $excel_value->state;

            //get personal info
            $SELECT_PERSONAL_INFO_QUERY = "SELECT a.company_id AS company_id, a.lname AS lname, a.fname AS fname, a.mname AS mname, b.company_id AS company_id, b.company_ind AS company_ind, b.biometrics_id AS biometrics_id, c.company_id AS company_id, c.payroll_group_ind AS group_ind FROM  " . Session::get('hris_database') . ".personal_information a LEFT JOIN " . Session::get('hris_database') . ".employee_information b ON (a.company_id = b.company_id) LEFT JOIN " . Session::get('hris_database') . ".employee_payroll_group c ON(a.company_id = c.company_id) WHERE b.biometrics_id = '$biometrics_id' AND c.deleted ='0'";
            $SELECT_PERSONAL_INFO = DB::connection('mysql3')->select($SELECT_PERSONAL_INFO_QUERY);
            
            $CHECK_DTR_RECORD_QUERY = "SELECT * FROM date_and_time_records WHERE company_id = '" . $SELECT_PERSONAL_INFO[0]->company_id . "' AND sched_date = '$sched_date'";
            $CHECK_DTR_RECORD = DB::connection('mysql2')->select($CHECK_DTR_RECORD_QUERY);

            if(!empty($CHECK_DTR_RECORD)){
                //update datetimein
                if($state == "C/In"){
                    if($CHECK_DTR_RECORD[0]->date_time_in == null || $CHECK_DTR_RECORD[0]->date_time_in == ""){
                        $update_dtr = DateTimeRecords::find($CHECK_DTR_RECORD[0]->id);
                        $update_dtr->date_time_in = $date_time;
                        $update_dtr->save();
                    }
                    else{
                        if($CHECK_DTR_RECORD[0]->date_time_in > $date_time){
                            $update_dtr = DateTimeRecords::find($CHECK_DTR_RECORD[0]->id);
                            $update_dtr->date_time_in = $date_time;
                            $update_dtr->save();
                        }
                    }
                }
                //end datetimein
                
                //update datetimeout
                else if($state == "C/Out"){
                    if($CHECK_DTR_RECORD[0]->date_time_out == null || $CHECK_DTR_RECORD[0]->date_time_out == ""){
                        $update_dtr = DateTimeRecords::find($CHECK_DTR_RECORD[0]->id);
                        $update_dtr->date_time_out = $date_time;
                        $update_dtr->save();
                    }
                    else{
                        if($CHECK_DTR_RECORD[0]->date_time_out < $date_time){
                            $update_dtr = DateTimeRecords::find($CHECK_DTR_RECORD[0]->id);
                            $update_dtr->date_time_out = $date_time;
                            $update_dtr->save();
                        }
                    }
                }
                //end datetimeout
                else{
                    $message = "ERROR1";
                    $error[] = $message;
                }

                //select schedule request
                // $GET_SCHEDULE_REQUEST_QUERY = "SELECT a.company_id, a.lname, a.fname, a.mname, b.company_ind, b.biometrics_id, c.payroll_group_ind AS group_ind, d.template_id AS sched_template_id, d.start_date, d.end_date, e.type, e.reg_in, e.reg_out, e.mon_in, e.mon_out, e.mon, e.tue_in, e.tue_out, e.tue, e.wed_in, e.wed_out, e.wed, e.thu_in, e.thu_out, e.thu, e.fri_in, e.fri_out, e.fri, e.sat_in, e.sat_out, e.sat, e.sun_in, e.sun_out, e.sun, e.flexihrs FROM " . Session::get('hris_database') . ".personal_information a LEFT JOIN " . Session::get('hris_database') . ".employee_information b ON (a.company_id = b.company_id) LEFT JOIN " . Session::get('hris_database') . ".employee_payroll_group c ON(a.company_id = c.company_id) LEFT JOIN hris_csi.employee_schedule_request d ON(a.company_id = d.company_id) LEFT JOIN " . Session::get('hris_database') . ".schedule_template e ON(d.template_id = e.ind) WHERE d.request_status ='APPROVED' AND b.biometrics_id ='$biometrics_id' AND c.deleted = '0' AND d.deleted = '0' AND '$sched_date' BETWEEN d.start_date AND d.end_date";
                // $GET_SCHEDULE_REQUEST = DB::connection('mysql3')->select($GET_SCHEDULE_REQUEST_QUERY);

                // if(!emopty($GET_SCHEDULE_REQUEST)){

                // }
                // else{
                    //select schedule
                //     $GET_SCHEDULE_QUERY = "SELECT a.company_id, a.lname, a.fname, a.mname, b.company_ind, b.biometrics_id, c.payroll_group_ind AS group_ind, d.template_id AS sched_template_id, e.type, e.reg_in, e.reg_out, e.mon_in, e.mon_out, e.mon, e.tue_in, e.tue_out, e.tue, e.wed_in, e.wed_out, e.wed, e.thu_in, e.thu_out, e.thu, e.fri_in, e.fri_out, e.fri, e.sat_in, e.sat_out, e.sat, e.sun_in, e.sun_out, e.sun, e.flexihrs FROM " . Session::get('hris_database') . ".personal_information a LEFT JOIN " . Session::get('hris_database') . ".employee_information b ON (a.company_id = b.company_id) LEFT JOIN " . Session::get('hris_database') . ".employee_payroll_group c ON(a.company_id = c.company_id) LEFT JOIN " . Session::get('hris_database') . ".employee_schedule d ON(a.company_id = d.company_id) LEFT JOIN " . Session::get('hris_database') . ".schedule_template e ON(d.template_id = e.ind) WHERE b.biometrics_id ='5' AND c.deleted = '0' AND d.deleted = '0'";
                //     $GET_SCHEDULE = DB::connection('mysql3')->select($GET_SCHEDULE_QUERY);
                // }
                

                //get date time in and date time out --- total hours
                $GET_TIME_QUERY = "SELECT date_time_in, date_time_out FROM date_and_time_records WHERE company_id = '" . $SELECT_PERSONAL_INFO[0]->company_id . "' AND sched_date = '$sched_date'";
                $GET_TIME = DB::connection('mysql2')->select($GET_TIME_QUERY);

                if(($GET_TIME[0]->date_time_in != null || $GET_TIME[0]->date_time_in != "") && ($GET_TIME[0]->date_time_out != null || $GET_TIME[0]->date_time_out != "")){

                    $date_time_in = new DateTime($GET_TIME[0]->date_time_in);
                    $date_time_out = new DateTime($GET_TIME[0]->date_time_out);

                    $interval = $date_time_out->diff($date_time_in);
                    $total_hours = round($interval->s / 3600 + $interval->i / 60 + $interval->h + $interval->days * 24, 2);

                    $update_dtr = DateTimeRecords::find($CHECK_DTR_RECORD[0]->id);
                    $update_dtr->reg_total_hrs = $total_hours;
                    $update_dtr->save();
                }
                else{
                    $message = "ABSENT" . " " .$SELECT_PERSONAL_INFO[0]->company_id;
                    $error[] = $message;
                }
                //end total hours
            }
            else{
                if($state == "C/In"){
                    $insert_dtr = new DateTimeRecords;
                    $insert_dtr->company_id = $SELECT_PERSONAL_INFO[0]->company_id;
                    $insert_dtr->company_ind = $SELECT_PERSONAL_INFO[0]->company_ind;
                    $insert_dtr->group_ind = $SELECT_PERSONAL_INFO[0]->group_ind;
                    $insert_dtr->employee_name = $SELECT_PERSONAL_INFO[0]->lname . ", " . $SELECT_PERSONAL_INFO[0]->fname . " " . $SELECT_PERSONAL_INFO[0]->mname;
                    $insert_dtr->date_time_in = $date_time;
                    $insert_dtr->sched_date = $sched_date;
                    $insert_dtr->created_by = auth()->user()->name;
                    $insert_dtr->lu_by = auth()->user()->name;
                    $insert_dtr->save();
                    $message = "SUCCESS INSERT IN";
                    $success[] = $message;

                }
                else if($state == "C/Out"){
                    $insert_dtr = new DateTimeRecords;
                    $insert_dtr->company_id = $SELECT_PERSONAL_INFO[0]->company_id;
                    $insert_dtr->company_ind = $SELECT_PERSONAL_INFO[0]->company_ind;
                    $insert_dtr->group_ind = $SELECT_PERSONAL_INFO[0]->group_ind;
                    $insert_dtr->employee_name = $SELECT_PERSONAL_INFO[0]->lname . ", " . $SELECT_PERSONAL_INFO[0]->fname . " " . $SELECT_PERSONAL_INFO[0]->mname;
                    $insert_dtr->date_time_out = $date_time;
                    $insert_dtr->sched_date = $sched_date;
                    $insert_dtr->created_by = auth()->user()->name;
                    $insert_dtr->lu_by = auth()->user()->name;
                    $insert_dtr->save();
                    $message = "SUCCESS INSERT IN";
                    $success[] = $message;
                }
                else{
                    $message = "ERROR2";
                    $error[] = $message;
                } 
            }

            $result = array(
                'error'=>$error,
                'success'=>$success,
            );
            echo json_encode($result);
        }
    }
    // public function submit(Request $res)
    // {
    //     $result = array();
    //     $error = array();
    //     $success = array();

    //     //get excel file
    //     $excel_path = $res->file->getRealPath();
    //     $excel_data = Excel::load($excel_path)->get();

    //     foreach($excel_data as $excel_value){
    //         $ac_no = "ac_no.";
    //         $biometric_id = $excel_value->$ac_no;
    //         // $emp_name = $excel_value->name;
    //         $time = date("Y-m-d H:i:s", strtotime($excel_value->time));
    //         $sched_date = date("Y-m-d", strtotime($excel_value->time));
    //         $state = $excel_value->state;
            
    //         $CHECK_DTR_RECORD_QUERY = "SELECT * FROM attendance_records WHERE biometric_id = '$biometric_id' AND sched_date = '$sched_date'";
    //         $CHECK_DTR_RECORD = DB::connection('mysql')->select($CHECK_DTR_RECORD_QUERY);

    //         if(!empty($CHECK_DTR_RECORD)){

    //             if($state == "C/In"){
    //                 if($CHECK_DTR_RECORD[0]->time_in == null || $CHECK_DTR_RECORD[0]->time_in == ""){
    //                     $update_dtr = AttendanceRecords::find($CHECK_DTR_RECORD[0]->id);
    //                     $update_dtr->time_in = $time;
    //                     $update_dtr->save();

    //                     $message = "SUCCESS C/IN UPDATE";
    //                     $success[] = $message;
    //                 }
    //                 else{
    //                     if($CHECK_DTR_RECORD[0]->time_in > $time){
    //                         $update_dtr = AttendanceRecords::find($CHECK_DTR_RECORD[0]->id);
    //                         $update_dtr->time_in = $time;
    //                         $update_dtr->save();

    //                         $message = "SUCCESS C/IN UPDATE";
    //                         $success[] = $message;
    //                     }
    //                 }
    //             }
    //             else if($state == "C/Out"){
    //                 if($CHECK_DTR_RECORD[0]->time_out == null || $CHECK_DTR_RECORD[0]->time_out == ""){
    //                     $update_dtr = AttendanceRecords::find($CHECK_DTR_RECORD[0]->id);
    //                     $update_dtr->time_out = $time;
    //                     $update_dtr->save();

    //                     $message = "SUCCESS C/OUT UPDATE";
    //                     $success[] = $message;
    //                 }
    //                 else{
    //                     if($CHECK_DTR_RECORD[0]->time_out < $time){
    //                         $update_dtr = AttendanceRecords::find($CHECK_DTR_RECORD[0]->id);
    //                         $update_dtr->time_out = $time;
    //                         $update_dtr->save();

    //                         $message = "SUCCESS C/OUT CHANGE GREATER THAN RECENT TIME OUT U_PDATE";
    //                         $success[] = $message;
    //                     }
    //                 }
    //             }
    //             else{
    //                 $message = "ERROR1";
    //                 $error[] = $message;
    //             }
    //         }
    //         else{

    //             if($state == "C/In"){
    //                 $insert_query = new AttendanceRecords;
    //                 $insert_query->biometric_id = $biometric_id;
    //                 $insert_query->employee_name = $emp_name;
    //                 $insert_query->time_in = $time;
    //                 $insert_query->sched_date = $sched_date;
    //                 $insert_query->created_by = auth()->user()->name;
    //                 $insert_query->lu_by = auth()->user()->name;
    //                 $insert_query->save();
    //                 $message = "SUCCESS INSERT IN";
    //                 $success[] = $message;

    //             }
    //             else if($state == "C/Out"){
    //                 $insert_query = new AttendanceRecords;
    //                 $insert_query->biometric_id = $biometric_id;
    //                 $insert_query->employee_name = $emp_name;
    //                 $insert_query->time_out = $time;
    //                 $insert_query->sched_date = $sched_date;
    //                 $insert_query->created_by = auth()->user()->name;
    //                 $insert_query->lu_by = auth()->user()->name;
    //                 $insert_query->save();
    //                 $message = "SUCCESS INSERT OUT";
    //                 $success[] = $message;
    //             }
    //             else{
    //                 $message = "ERROR2";
    //                 $error[] = $message;
    //             } 
    //         }

    //         $result = array(
    //             'error'=>$error,
    //             'success'=>$success,
    //         );
    //         echo json_encode($result);  
    //     }
    // }
}