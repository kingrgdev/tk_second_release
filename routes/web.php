<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/dashboard', function () {
//     return view('auth/login');
// });

// Route::get('/', function () {
//     return view('/dashboard');
// });
Route::get('/', function () { // root if the user is login
    if (Auth::guest()){
    return view('auth.login');
    }
    else{
    //return view('/');
        return view('dashboard');     
    }

});

Route::get('/home', function () { // root if the user is login
    if (Auth::guest()){
        return view('auth.login');
    }
    else{
       // return view('/');
       return view('dashboard');   
    }

});
Auth::routes();

//homepage
// Route::get('/', 'UsersController\\DashboardController@index')->name('dashboard');

Route::get('/errorpage', 'ErrorController\\ErrorController@index')->name('errorpage');

//ADMIN MODULE

//createuser


//createusertype
Route::get('/createusertype', 'AdminController\\CreateUserTypeController@index');
Route::get('createusertype/getusertype', 'AdminController\\CreateUserTypeController@getUserType')->name('getusertype');//get user type
Route::get('createusertype/checkusertype', 'AdminController\\CreateUserTypeController@checkUserType')->name('checkusertype');//check user type
Route::post('createusertype/saveusertype', 'AdminController\\CreateUserTypeController@saveUserType')->name('saveusertype');//save user type
Route::post('/createusertype/editusertype', 'AdminController\\CreateUserTypeController@editUserType')->name('editusertype'); //edit user type
Route::get('/createusertype/getmanageaccess', 'AdminController\\CreateUserTypeController@getManageAccess')->name('getmanageaccess');//get manage access
Route::post('/createusertype/updateaccess', 'AdminController\\CreateUserTypeController@updateAccess')->name('updateaccess'); //update access
Route::post('/createusertype/deleteusertype', 'AdminController\\CreateUserTypeController@deleteUserType')->name('deleteusertype'); //delete user type
///

//createuser
Route::get('register/getuser', 'Auth\\RegisterController@getUser')->name('getuser');//get user
Route::get('register/getcompany', 'Auth\\RegisterController@getCompany')->name('getcompany');//get company
Route::get('register/getdepartment', 'Auth\\RegisterController@getDepartment')->name('getdepartment');//get department
Route::get('register/getusertypename', 'Auth\\RegisterController@getUserTypeName')->name('getusertypename');//get user type name
Route::get('register/searchuser', 'Auth\\RegisterController@searchUser')->name('searchuser');//search user
Route::get('register/checkuser_add', 'Auth\\RegisterController@checkUser_add')->name('checkuser_add');//check user add
Route::get('register/checkuser_edit', 'Auth\\RegisterController@checkUser_edit')->name('checkuser_edit');//check user edit
Route::post('register/edituser', 'Auth\\RegisterController@editUser')->name('edituser');//edit user
Route::post('register/deleteuser', 'Auth\\RegisterController@deleteUser')->name('deleteuser');//delete user
///

//add alteration
Route::get('/addalteration', 'AdminController\\AddAlterationController@index');
Route::get('/addalteration/getemployeeinfo', 'AdminController\\AddAlterationController@getEmployeeInfo')->name('getemployeeinfo');//get employee list
Route::get('/addalteration/getteam', 'AdminController\\AddAlterationController@getTeam')->name('getteam');//get employee list
Route::get('/addalteration/searchemployee', 'AdminController\\AddAlterationController@searchEmployee')->name('searchemployee');//get employee list
Route::get('/addalteration/checkalter', 'AdminController\\AddAlterationController@checkAlter')->name('checkalter');//check alter
Route::post('/addalteration/addalter', 'AdminController\\AddAlterationController@addAlter')->name('addalter');//add alter
Route::get('/addalteration/getalterationrecord', 'AdminController\\AddAlterationController@getAlterationRecord')->name('getalterationrecord');//get alteration record
Route::post('/addalteration/cancelalteration', 'AdminController\\AddAlterationController@cancelAlteration')->name('cancelalteration');//cancel alteration
Route::get('/addalteration/searchalterationrecord', 'AdminController\\AddAlterationController@searchAlterationRecord')->name('searchalterationrecord');//cancel alteration

//add overtime
Route::get('/addovertime', 'AdminController\\AddOvertimeController@index');
Route::get('/addovertime/checkovertime', 'AdminController\\AddOvertimeController@checkOvertime')->name('checkovertime');//check overtime
Route::post('/addovertime/addovertime', 'AdminController\\AddOvertimeController@addOvertime')->name('addovertime');//add overtime
Route::get('/addovertime/searchovertimerecord', 'AdminController\\AddOvertimeController@searchOvertimeRecord')->name('searchovertimerecord');//search overtime
Route::get('/addovertime/getovertimerecord', 'AdminController\\AddOvertimeController@getOvertimeRecord')->name('getovertimerecord');//get overtime
Route::post('/addovertime/cancelovertime', 'AdminController\\AddOvertimeController@cancelOvertime')->name('cancelovertime');//cancel overtime

//add leave
Route::get('/addleave', 'AdminController\\AddLeaveController@index');
Route::get('/addleave/getleavetype', 'AdminController\\AddLeaveController@getLeaveType')->name('getleavetype');//get leave type
Route::get('/addleave/getdays', 'AdminController\\AddLeaveController@getDays')->name('getdays');//get days
Route::get('/addleave/checkleave', 'AdminController\\AddLeaveController@checkLeave')->name('checkleave');//get days
Route::post('/addleave/addleave', 'AdminController\\AddLeaveController@addLeave')->name('addleave');//add leave
Route::get('/addleave/searchleaverecord', 'AdminController\\AddLeaveController@searchLeaveRecord')->name('searchleaverecord');//search leave records
Route::get('/addleave/getleaverecords', 'AdminController\\AddLeaveController@getLeaveRecords')->name('getleaverecords');//get leave
Route::post('/addleave/cancelleave', 'AdminController\\AddLeaveController@cancelLeave')->name('cancelleave');//get leave

//add work schedule
Route::get('/addworkschedule', 'AdminController\\AddWorkScheduleController@index');
Route::get('/addworkschedule/getscheduletype', 'AdminController\\AddWorkScheduleController@getScheduleType')->name('getscheduletype');//get schedule type
Route::get('/addworkschedule/getschedule', 'AdminController\\AddWorkScheduleController@getSchedule')->name('getschedule');//get schedule type
Route::get('/addworkschedule/getemployeeschedule', 'AdminController\\AddWorkScheduleController@getEmployeeSchedule')->name('getemployeeschedule');//get employee schedule
Route::get('/addworkschedule/searchemployeeschedule', 'AdminController\\AddWorkScheduleController@searchEmployeeSchedule')->name('searchemployeeschedule');//search employee
Route::get('/addworkschedule/checkschedulerequest', 'AdminController\\AddWorkScheduleController@checkScheduleRequest')->name('checkschedulerequest');//check schedule request employee 
Route::post('/addworkschedule/addschedulerequest', 'AdminController\\AddWorkScheduleController@addScheduleRequest')->name('addschedulerequest');//add schedule request employee 
Route::get('/addworkschedule/getschedulerequestrecord', 'AdminController\\AddWorkScheduleController@getScheduleRequestRecord')->name('getschedulerequestrecord');//get schedule request record 
Route::post('/addworkschedule/cancelschedulerequest', 'AdminController\\AddWorkScheduleController@cancelScheduleRequest')->name('cancelschedulerequest');//cancel schedule request record 
Route::get('/addworkschedule/searchemployeeschedulerecordrequest', 'AdminController\\AddWorkScheduleController@searchEmployeeScheduleRequestRecord')->name('searchemployeeschedulerecordrequest');//search employee schedule request
Route::get('/addworkschedule/checkscheduletemplate', 'AdminController\\AddWorkScheduleController@checkScheduleTemplate')->name('checkscheduletemplate');//check schedule template
Route::post('/addworkschedule/addscheduletemplate', 'AdminController\\AddWorkScheduleController@addScheduleTemplate')->name('addscheduletemplate');//add schedule template

//import biometrics
Route::get('/importbiometrics', 'AdminController\\ImportBiometrics@index');
Route::post('/uploadbiometrics', 'AdminController\\ImportBiometrics@uploadBiometrics')->name('uploadbiometrics');//add biometrics




// Route::get('attendance-list', 'AdminController\\ImportBiometrics@AttendanceList')->name('attendance-list');
// Route::post('attendance-import', 'AdminController\\ImportBiometrics@ImportAttendance')->name('attendance-import');
// Route::post('submit', 'AdminController\\ImportBiometrics@submit')->name('submit');


// Route::post('submit', 'AdminController\\ImportBiometrics@submit');
//ADMIN MODULE

/////////////////

//USER MODULE

//userprofile
Route::get('/myprofile/userprofile', 'UsersController\\UserProfileController@user_profile')->name('userprofile');
// Route::get('/myprofile/show', 'UsersController\\DropDownItemController@show_profile')->name('myprofile');

//timerecords
Route::get('/timerecords', 'UsersController\\TimeRecordsController@index');
Route::get('timerecords/printdate', 'UsersController\\TimeRecordsController@print_date_now')->name('printdate');
Route::get('timerecords/filterdate', 'UsersController\\TimeRecordsController@filter_dates')->name('filterdate');
Route::post('timerecords/applyalteration', 'UsersController\\TimeRecordsController@apply_alteration')->name('applyalteration');
//timerecords

//Punch Alteration Records
Route::get('/timerecords/view_punch_alteration', 'UsersController\\PunchAlterationRecordController@index');
Route::get('/timerecords/refresh', 'UsersController\\PunchAlterationRecordController@refresh_table')->name('refreshtable_alteration');
Route::post('/timerecords/cancelalteration', 'UsersController\\PunchAlterationRecordController@cancel_alteration')->name('cancelalteration');
Route::get('/timerecords/show_all', 'UsersController\\PunchAlterationRecordController@search_by_all')->name('searchall');
Route::get('/timerecords/show_all_altered_date', 'UsersController\\PunchAlterationRecordController@search_by_alterationdate')->name('searchalteration');
Route::get('/timerecords/show_all_date_applied', 'UsersController\\PunchAlterationRecordController@search_by_dateapplied')->name('searchapplied');
Route::get('/timerecords/show_all_status_all', 'UsersController\\PunchAlterationRecordController@search_by_allstatus')->name('statusall');
Route::get('/timerecords/show_all_status_pending', 'UsersController\\PunchAlterationRecordController@search_by_pendingstatus')->name('statuspending');
Route::get('/timerecords/show_all_status_cancel', 'UsersController\\PunchAlterationRecordController@search_by_cancelledstatus')->name('statuscancel');
Route::get('/timerecords/show_all_status_approve', 'UsersController\\PunchAlterationRecordController@search_by_approvedstatus')->name('statusapprove');
Route::get('/timerecords/show_all_status_retracted', 'UsersController\\PunchAlterationRecordController@search_by_retractedstatus')->name('statusretracted');
Route::get('/timerecords/show_all_status_rejected', 'UsersController\\PunchAlterationRecordController@search_by_rejectedstatus')->name('statusrejected');

//Punch Alteration History
Route::get('/timerecords/view_punch_history', 'UsersController\\PunchAlterationRecordController@punch_history_view');
Route::get('/timerecords/view_punch_history/historylist', 'UsersController\\PunchAlterationRecordController@show_history_list')->name('historylist');
Route::get('/timerecords/view_punch_history/historyfilter', 'UsersController\\PunchAlterationRecordController@show_history_filter')->name('historyfilter');
//Punch Alteration History

//Punch Alteration Records

//overtime records
Route::get('/overtimerecords', 'UsersController\\OvertimeRecordsController@index');


//For integration with clint//
    ////----Overtime----////
        Route::get('overtimerecords/printovertime', 'UsersController\\OvertimeRecordsController@print_overtime_now')->name('printovertime');
        Route::post('overtimerecords/cancelovertime', 'UsersController\\OvertimeRecordsController@cancel_overtime')->name('cancelovertime');
        Route::get('overtimerecords/filterdates', 'UsersController\\OvertimeRecordsController@filter_dates')->name('filterdates');
        Route::post('overtimerecords/saveovertime', 'UsersController\\OvertimeRecordsController@save_overtime')->name('saveovertime');
        
        Route::get('navbar/numnotifications', 'Auth\\NotificationController@numnotifications')->name('numnotifications');
        Route::get('navbar/viewnotifications', 'Auth\\NotificationController@viewnotifications')->name('viewnotifications');
    
    ////----WorkSchedule----////
        Route::get('/workschedulerecords', 'UsersController\\WorkScheduleController@index');
        Route::get('workschedulerecords/printschedule', 'UsersController\\WorkScheduleController@print_schedule')->name('printschedule');
        Route::get('workschedulerecords/printschedulelist', 'UsersController\\WorkScheduleController@print_schedule_list')->name('printschedulelist');
        Route::post('workschedulerecords/saveschedulerequest', 'UsersController\\WorkScheduleController@save_schedule_request')->name('saveschedulerequest');
        
        Route::post('workschedulerecords/savecustomregular', 'UsersController\\WorkScheduleController@save_custom_regular')->name('savecustomregular');
        Route::post('workschedulerecords/cancelrequest', 'UsersController\\WorkScheduleController@cancel_schedule_request')->name('cancelrequest');
//For integration with clint//



//USER MODULE
