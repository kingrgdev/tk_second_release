@extends('layouts/app')

@section('content')

<style>
#tableScheduleRecord{
    text-align:center;
}
#tableScheduleRecord th{
    border-bottom:1px solid #000;
    /* border-top:1px solid #000; */
}
#tableScheduleList th{
    border-bottom:1px solid #000;
    /* border-top:1px solid #000; */
}
#tableScheduleListTemplate th{
    border-bottom:1px solid #000;
    /* border-top:1px solid #000; */
}
#tableScheduleListTemplate tbody{
    border-bottom:1px solid #000;
    /* border-top:1px solid #000; */
}
.p_load{
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .8) url('../images/loading.gif') no-repeat 50% 50%;
        z-index: 1000;
    }
</style>



<div id="pageContainer" class="container-fluid">

    <h1>Time Records</h1>

    <!-- This is your Userprofile -->
        @include('modules.usersmodule.userprofile.user_profile')
    <!-- This is your Userprofile -->

    <div id="moduleContainer">

            <label class="pull-right"><b>Apply for Change Work Schedule (CWS)? </b><span id="scheduleIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
            <br><br>

            <!-- Apply Work Schedule -->
            <div id="hideScheduleField" style="display:none">
                <div class="container">
                    
                        {{-- CATEGORY SCHEDULE SETTINGS --}}
                        <form class="form-inline">
                            <label><input id="rdB_ScheduleTemplates" type="radio" name="cat_schedule" value="rdB_ScheduleTemplates" checked><strong>Choose from Schedule Templates &nbsp;</strong></label>
                            <label><input id="rdB_CustomSchedule" type="radio" name="cat_schedule" value="rdB_CustomSchedule"><strong>Create a Custom Schedule &nbsp;</strong></label>
                        </form>
                        {{-- CATERGORY SCHEDULE SETTINGS END --}}


                        {{-- SCHEDULE TEMPLATE --}}
                        <div id="scheduleTemplates" style="display:none">
                            <br>
                            <form class="form-inline">
                                {{-- START DATE --}}
                                <div class = "form-group">
                                    <div class="form__group col-md-12 fg_margin input-group date" data-target-input="nearest">
                                        <input id="startDate_CWS" name = "startDate_CWS" type="text" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#startDate_CWS" data-toggle="datetimepicker">
                                        <label for="startDate_CWS" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Start Date</label>
                                    </div>
                                </div>
                                {{-- START DATE END --}}
                                <div class = "form-group">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                {{-- END DATE --}}
                                <div class = "form-group" id="irregularEndDate">
                                    <div class="form__group col-md-12 fg_margin input-group date" data-target-input="nearest">
                                        <input id="endDate_CWS" name = "endDate_CWS" type="text" class="datetimepicker-input form__field" placeholder="End Date" data-target="#endDate_CWS" data-toggle="datetimepicker">
                                        <label for="endDate_CWS" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;End Date</label>
                                    </div>
                                </div>
                                {{-- END DATE END --}}
        
                                <div class = "form-group">
                                    <label><input id="chk_EDIndefinite" type="checkbox" name="end_date_indefinite"><strong>Indefinite &nbsp;</strong></label>
                                </div>
                            </form>
                            <br>
                            <br>
                            <div id="divScheduleListTemplate" class="table-responsive col-md-12">
                                <table id="tableScheduleListTemplate" name="tableScheduleListTemplate" class="table" style="width:100%; text-align:center;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Template Name</th>
                                            <th>Shift Time</th>
                                            <th>Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td><!--<input id="rdB_CustomSchedule" type="radio" name="optradio" value="rdB_CustomSchedule">-->
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                
                                </table>
                            </div>
                        </div>
                        {{-- SCHEDULE TEMPLATE END --}}
                        
                        {{-- CUSTOM SCHEDULE --}}
                        <div id="customSchedule" style="display:none">
                            <div id="customScheduleList">
                                
                                <br>
                                {{-- Location --}}
                                <form class="form-inline">

                                <div class="form__group col-md-8">
                                    <input id="templateName" name="templateName" type="text" class="form__field" placeholder="Template Name">
                                    <label for="templateName" class="span-header form__label"><i class="icon-right fa fa-map-marker" aria-hidden="true"></i>Template Name</label>
                                </div>
                                    
                                </form>

                                <br>

                                {{-- REASON WORK SCHED --}}
                                <form class="form-inline">
                                    <div class="form__group col-md-8">
                                        <textarea id="scheduleDescription" name = "txtReason_apply" class="form__field" placeholder="Reason For Applying Work Schedule *"></textarea>
                                        <label for="scheduleDescription" class="span-header form__label"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Reason For Applying Work Schedule *</label>
                                    </div>              
                                </form>
                                <br>
                                {{-- REASON WORK SCHED END --}}
                                <form class="form-inline">
                                    <!-- lunch out -->
                                    <div class="form__group col-md-2 input-group date my-2" data-target-input="nearest">
                                        <input id="addLunchOut" name="addLunchOut" type="text" class="datetimepicker-input form__field" data-target="#addLunchOut" data-toggle="datetimepicker" placeholder="Lunch Out">
                                        <label for="addLunchOut" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch Out</label>
                                    </div>
                                    <!-- end -->
                                    <div id="padding" style="margin-left:10px";></div>
                                    <!-- lunch in -->
                                    <div class="form__group col-md-2 input-group date my-2" data-target-input="nearest">
                                        <input id="addLunchIn" name="addLunchIn" type="text" class="datetimepicker-input form__field" data-target="#addLunchIn" data-toggle="datetimepicker" placeholder="Lunch In">
                                        <label for="addLunchIn" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch In</label>
                                    </div>
                                    <!-- end -->
                                    <div id="padding" style="margin-left:10px";></div>
                                    <!-- total hours -->
                                    <input id="hiddenLunchHours" name="hiddenLunchHours" type="hidden">
                                    <div class="form__group col-md-2 my-2">
                                        <input id="txtLunchHours" name="txtLunchHours" type="number" class="form__field" placeholder="Lunch Hours" disabled>
                                        <label for="txtLunchHours" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch Hours</label>
                                    </div>
                                    <!-- end -->
                                </form>

                                <br>
                                <form class="form-inline">
                                    <label><input id="rdB_RegularShift" type="radio" name="shift_list" value="rdB_RegularShift" checked>Regular Shift</label>
                                    <label><input id="rdB_IrregularShift" type="radio" name="shift_list" value="rdB_IrregularShift">Irregular Shift</label>
                                    <label><input id="rdB_FlexiShift" type="radio" name="shift_list" value="rdB_FlexiShift">Flexi Shift</label>
                                    <label><input id="rdB_FreeShift" type="radio" name="shift_list" value="rdB_FreeShift">Free Shift</label>
                                </form>

                                {{-- REGULAR SHIFT --}}
                                <div id="regularShift" style="display:none">
                                    <form class="form-inline">

                                        {{-- SHIFT IN --}}
                                        
                                        <div class="form-group">
                                            <div class="form__group col-md-12 fg_margin input-group date" data-target-input="nearest">
                                                <input id="dtp_RegularShiftIn" name = "dtp_RegularShiftIn" type="text" class="datetimepicker-input form__field" placeholder="Shift In" data-target="#dtp_RegularShiftIn" data-toggle="datetimepicker">
                                                <label for="dtp_RegularShiftIn" class="span-header form__label"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Shift In</label>
                                            </div>
                                        </div>

                                        {{-- SHIFT IN END --}}

                                        <div id="padding" style="margin-left:10px";></div>

                                        {{-- SHIFT OUT --}}
                                        
                                        <div class="form-group">
                                            <div class="form__group col-md-12 fg_margin input-group date" data-target-input="nearest">
                                                <input id="dtp_RegularShiftOut" name = "dtp_RegularShiftOut" type="text" class="datetimepicker-input form__field" placeholder="Shift Out" data-target="#dtp_RegularShiftOut" data-toggle="datetimepicker">
                                                <label for="dtp_RegularShiftOut" class="span-header form__label"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Shift Out</label>
                                            </div>
                                        </div>
                                        {{-- SHIFT OUT END --}}
                                    </form>
                                    <br>
                                    <br>
                                    {{-- REGULAR TABLE --}}
                                    <div class="padding-table" style="padding-bottom:10px;">
                                        <div class="table-responsive col-md-12">
                                            <table id="tableScheduleList" name="example" class="table" style="width:100%; text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Days</th>
                                                        <th>Is Working Rest Day?</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- MONDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_mon" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="1"></td>
                                                        <td>
                                                            <label>Monday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_mon" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="1" disabled>
                                                        </td>
                                                    </tr>

                                                    {{-- TUESDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_tue"  class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="2"></th>
                                                        <td>
                                                            <label>Tuesday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_tue" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="2" disabled>
                                                        </td>
                                                    </tr>
                                                    {{-- WEDNESDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_wed" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="3"></th>
                                                        <td>
                                                            <label>Wednesday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_wed" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="3" disabled>
                                                        </td>
                                                    </tr>
                                                    {{-- THURSDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_thurs" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="4"></th>
                                                        <td>
                                                            <label>Thursday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_thurs" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="4" disabled>
                                                        </td>
                                                    </tr>
                                                    {{-- FRIDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_fri" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="5"></th>
                                                        <td>
                                                            <label>Friday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_fri" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="5" disabled>
                                                        </td>
                                                    </tr>
                                                    {{-- SATURDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_sat" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="6"></th>
                                                        <td>
                                                            <label>Saturday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_sat" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="6" disabled>
                                                        </td>
                                                    </tr>
                                                    {{-- SUNDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable_days_sun" class = "chk_RegularShiftTable_days" type="checkbox" name="chk_RegularShiftTable_days[]" value="7"></th>
                                                        <td>
                                                            <label>Sunday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable_rest_sun" class = "chk_RegularShiftTable_rest" type="checkbox" name="chk_RegularShiftTable_rest[]" value="7" disabled>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Days</th>
                                                        <th>Is Working Rest Day?</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                {{-- REGULAR SHIFT END --}}

                                {{-- IRREGULAR SHIFT --}}
                                <div id="irregularShift" style="display:none";>
                                    <div class="padding-table" style="padding-bottom:10px;">
                                        <div class="table-responsive col-md-12">
                                            <table id="tableScheduleList" name="example" class="table" style="width:100%; text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Days</th>
                                                        <th>Is Working Rest Day?</th>
                                                        <th>Shift In</th>
                                                        <th>Shift Out</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- MONDAY --}}
                                                    <tr>
                                                        <td><input id="chk_IrregularShiftTable" type="checkbox" name="irregular_shift" value="1"></td>
                                                        <td>
                                                            <label>Monday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_IrregularShiftTable" type="checkbox" name="irregular_shift" value="1">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_mon" name = "dtp_IrregularShiftIn_mon" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_mon" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_mon" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_mon" name = "dtp_IrregularShiftOut_mon" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_mon" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_mon" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    {{-- TUESDAY --}}
                                                    <tr>
                                                        <td><input id="chk_IrregularShiftTable" type="checkbox" name="irregular_shift" value="2"></th>
                                                        <td>
                                                            <label>Tuesday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_IrregularShiftTable" type="checkbox" name="irregular_shift" value="2">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_tue" name = "dtp_IrregularShiftIn_tue" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_tue" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_tue" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_tue" name = "dtp_IrregularShiftOut_tue" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_tue" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_tue" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- WEDNESDAY --}}
                                                    <tr>
                                                        <td><input id="chk_IrregularShiftTable" type="checkbox" name="irregular_shift" value="3"></th>
                                                        <td>
                                                            <label>Wednesday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="3">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_wed" name = "dtp_IrregularShiftIn_wed" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_wed" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_wed" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_wed" name = "dtp_IrregularShiftOut_wed" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_wed" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_wed" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- THURSDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="4"></th>
                                                        <td>
                                                            <label>Thursday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="4">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_thu" name = "dtp_IrregularShiftIn_thu" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_thu" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_thu" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_thu" name = "dtp_IrregularShiftOut_thu" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_thu" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_thu" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- FRIDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="5"></th>
                                                        <td>
                                                            <label>Friday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="5">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_fri" name = "dtp_IrregularShiftIn_fri" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_fri" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_fri" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_fri" name = "dtp_IrregularShiftOut_fri" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_fri" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_fri" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- SATURDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="6"></th>
                                                        <td>
                                                            <label>Saturday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="6">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_sat" name = "dtp_IrregularShiftIn_sat" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_sat" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_sat" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_sat" name = "dtp_IrregularShiftOut_sat" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_sat" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_sat" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- SUNDAY --}}
                                                    <tr>
                                                        <td><input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="7"></th>
                                                        <td>
                                                            <label>Sunday</label><br>
                                                        </td>
                                                        <td>
                                                            <input id="chk_RegularShiftTable" type="checkbox" name="regular_shift" value="7">
                                                        </td>
                                                        {{-- TABLE SHIFT IN --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>

                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftIn_sun" name = "dtp_IrregularShiftIn_sun" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#dtp_IrregularShiftIn_sun" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftIn_sun" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time In</label>
                                                                </div>
                                                            </div>

                                                            
                                                        </td>
                                                        {{-- TABLE SHIFT OUT --}}
                                                        <td>
                                                            <div class="form-group" style="width:200px";>
                                                                <div class="form__group input-group date" data-target-input="nearest">
                                                                    <input id="dtp_IrregularShiftOut_sun" name = "dtp_IrregularShiftOut_sun" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#dtp_IrregularShiftOut_sun" data-toggle="datetimepicker">
                                                                    <label for="dtp_IrregularShiftOut_sun" class="span-header form__label"><i class="icon-right fa fa-clock-o"></i>&nbsp;Time Out</label>
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                {{-- IRREGULAR SHIFT END --}}
                                <div id="flexiShift" style="display:none";>
                                    <label>Days :</label>
                                    <form class="form-inline">
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="1">Monday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="2">Tuesday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="3">Wednesday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="4">Thursday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="5">Friday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="6">Saturday</label>
                                        <label><input id="chk_FlexiShift" type="checkbox" name="flexi_shift" value="7">Sunday</label>
                                    </form>
                                    <br>
                                    <div class="form-group" style="width:50%";>
                                        <div class="form__group col-md-8">
                                            <input id="flexi_hours" name="flexi_hours" type="text" class="form__field" placeholder="Flexible Hours">
                                            <label for="flexi_hours" class="span-header form__label"><i class="icon-right fa fa-map-marker" aria-hidden="true"></i>Flexible Hours</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- CUSTOM SCHEDULE; --}}         
                        
                        <br>
                        <center>
                            <input id="btnApplyRequest" name="btnApplyRequest" class="btnApplyRequest btn btn-sm button blue" type="button" value="Apply Schedule Request" style="width:220px;"/>
                        </center>
                    
                </div>

            </div>
            <br><br>

        <div class="p_load" id="loader"></div>
        <div id="divScheduleRecord"class="table-responsive">
            <table id="tableScheduleRecord" name="tableScheduleRecord" class="tableScheduleRecord table table-hover" style="width:100%">
                <col>
                <colgroup span="2"></colgroup>
                <colgroup span="2"></colgroup>
                <thead>
                    <tr class="header" style="background:#f7f7f7;">
                        <th colspan="12" class="text-center">WORK SCHEDULE RECORDS</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Date Applied</th>
                        <th rowspan="2">Start Date</th>
                        <th rowspan="2">End Date</th>
                        <th rowspan="2" >Shift Type</th>
                        <th rowspan="2" >Days</th>
                        <th rowspan="2" >Applied Time of Shift</th>
                        <th colspan="2" scope="colgroup" style="border-top:1px solid #000;">Approval History</th>
                        <tr>
                            
                            <th scope="col">Level 1</th>
                            <th scope="col" >Level 2</th>
                            <th style="border-top:0px;">
                                Status
                            </th>
                            <th style="border-top:0px;">Actions</th>
                        </tr>
                    </tr>          
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>    
                        <td></td> 
                        <td></td>    
                        <td></td>  
                        <td></td> 
                        <td></td>
                        <td></td> 
                    </tr>
                </tbody>
            </table>
        </div>
            
    </div>

</div>


<script>

//cancel overtime
$(document).on("click", ".btnCancel", function(){
        
        var id_to_cancel = $(this).data("add");
        var c = confirm("Do you want to cancel this overtime?");

        if(c == true)
        {
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('cancelrequest') }}",
                method: "POST",
                data:{id_to_cancel: id_to_cancel}, 
                dataType: "json",
                success:function(data)
                {
                    alert(data);
                    refresh_schedule_list();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
//cancel alteration

$('#tableScheduleListTemplate').DataTable({
    "serverSide": false, 
    "retrieve": true, 
    "bStateSave": true,
    "ordering": false
});

//function refresh 
refresh_Table();
function refresh_Table(){
    $.ajax({
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ route('printschedulelist') }}",
        method: "GET",
        data:{}, 
        success:function(data)
        {
            
            $('#divScheduleListTemplate').html(data);
            $('#tableScheduleListTemplate').html(data);
            
            // $('#tableScheduleListTemplate').DataTable({
            //     "serverSide": false, 
            //     "retrieve": true,
            //     "bStateSave": true,
            //     "ordering": false
            // });
        },
        error: function(xhr, ajaxOptions, thrownError){
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}




//Work Schedule Request Form
$(document).on("click", "#scheduleIcon", function(){
        if($("#hideScheduleField").is(":visible")){

            $("#scheduleIcon").removeClass("fa-caret-square-o-down");
            $("#scheduleIcon").addClass("fa fa-caret-square-o-right");
            $("#hideScheduleField").stop().slideUp(150);
        }
        else{

            $("#scheduleIcon").removeClass("fa-caret-square-o-right");
            $("#scheduleIcon").addClass("fa-caret-square-o-down");
            $("#hideScheduleField").stop().slideDown(150);
            
        }
    });
//Work Schedule Request Form


//Print Work Schedule Request
    $('#tableOvertimeRecord').DataTable({
        "serverSide": false, 
        "retrieve": true, 
        "bStateSave": true,
        "ordering": false
    });

    //function refresh 
    refresh_schedule_list();
    function refresh_schedule_list(){
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('printschedule') }}",
            method: "GET",
            data:{}, 
            success:function(data)
            {
                
                $('#divScheduleRecord').html(data);
                
                $('#tableScheduleRecord').DataTable({
                    "serverSide": false, 
                    "retrieve": true,
                    "bStateSave": true,
                    "ordering": false
                });
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
//Print Work Schedule Request
</script>


<script>
    //Save Schedule Request
    $(document).on("click", ".btnApplyRequest", function(){
        var sched_temp = $("input[type=radio][name=cat_schedule]:checked").val();
        var custom_sched = $("input[type=radio][name=cat_schedule]:checked").val();
        if(sched_temp == "rdB_ScheduleTemplates"){

            var sched_temp_startDate = $("#startDate_CWS").val();
            var sched_temp_endDate = $("#endDate_CWS").val();
            var ind = document.getElementById("chk_EDIndefinite");
            var optradio = $("input[name='optradio']:checked").val();

            if(ind.checked == true)
            {
                if(sched_temp_startDate == "")
                {
                    alert("Start Date Field Required!");
                }
                else if(!$("input[name='optradio']:checked").val())
                {
                    alert("Choose your Schedule Template!");
                }
                else
                {
                    var c = confirm("Apply this Work Schedule Request?");
                    if(c == true)
                    {
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('saveschedulerequest') }}",
                            method: "POST",
                            dataType: "json",
                            data: {
                                sched_temp_startDate:sched_temp_startDate,
                                optradio:optradio,
                                ind:"TRUE"
                            },
                            success:function(data)
                            {
                                if(data.error.length > 0){
                                    alert(data.error[0]);
                                }
                                if(data.success.length > 0){
                                    alert(data.success[0]);
                                    refresh_schedule_list();
                                }
                                
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
                }
                
            }
            else if(ind.checked == false)
            {
                if(sched_temp_startDate == "")
                {
                    alert("Start Date Field Required!");
                }
                else if(sched_temp_endDate == "")
                {
                    alert("End Date Field Required!");
                }
                else if(!$("input[name='optradio']:checked").val())
                {
                    alert("Choose your Schedule Template!");
                }
                else
                {
                    var c = confirm("Apply this Work Schedule Request?");
                    if(c == true)
                    {
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('saveschedulerequest') }}",
                            method: "POST",
                            dataType: "json",
                            data: {
                                sched_temp_startDate:sched_temp_startDate,
                                sched_temp_endDate:sched_temp_endDate,
                                optradio:optradio,
                                ind:"FALSE"
                            },
                            success:function(data)
                            {
                                if(data.error.length > 0){
                                    alert(data.error[0]);
                                }
                                if(data.success.length > 0){
                                    alert(data.success[0]);
                                    refresh_schedule_list();
                                }
                                
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
                }
            }
        }
        else if(custom_sched == "rdB_CustomSchedule")
        {
            var shift_type = $("input[type=radio][name=shift_list]:checked").val();
            
            if(shift_type == "rdB_RegularShift"){

                var templateName = $("#templateName").val();
                var scheduleDescription = $("#scheduleDescription").val();
                
                var regShiftIn = $("#dtp_RegularShiftIn").val();
                var regShiftOut = $("#dtp_RegularShiftOut").val();
                
                var chkDays = document.getElementsByName('chk_RegularShiftTable_days[]');  
                var dataDays = [];
                for (var i = 0; i < chkDays.length; i++){
                    if (chkDays[i].checked){
                        dataDays.push(chkDays[i].value);
                    }
                }
                var chkRest = document.getElementsByName('chk_RegularShiftTable_rest[]');  
                var dataRest = [];
                for (var x = 0; x < chkRest.length; x++){
                    if (chkRest[x].checked){
                        dataRest.push(chkRest[x].value);
                    }
                }
                
                var addLunchOut = $("#addLunchOut").val();
                var addLunchIn = $("#addLunchIn").val();
                var hiddenLunchHours = $("#hiddenLunchHours").val();

                if(templateName == "")
                {
                    alert("Template Name Field Required!");
                }
                else if(scheduleDescription == "")
                {
                    alert("Schedule Description Field Required!");
                }
                else if(addLunchOut == "")
                {
                    alert("Lunch Out Field Required!");
                }
                else if(addLunchIn == "")
                {
                    alert("Lunch In Field Required!");
                }
                else if(addLunchOut >= addLunchIn)
                {
                    alert("Lunch Out must be greater than Lunch In!");
                }
                else if(regShiftIn == "")
                {
                    alert("Shift In Field Required!");
                }
                else if(regShiftOut == "")
                {
                    alert("Shift Out Field Required!");
                }
                else if(regShiftIn >= regShiftOut)
                {
                    alert("Shift In must be greater than Shift Out!");
                }
                else
                {
                    var c = confirm("Apply this Work Schedule Request?");
                    if(c == true)
                    {
                        $.ajax({

                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('savecustomregular') }}",
                            method: "POST",
                            dataType: "json",
                            data: {
                                regShiftIn:regShiftIn,
                                regShiftOut:regShiftOut,
                                templateName:templateName,
                                scheduleDescription:scheduleDescription,
                                dataDays:dataDays,
                                dataRest:dataRest,
                                addLunchOut:addLunchOut,
                                addLunchIn:addLunchIn,
                                hiddenLunchHours:hiddenLunchHours
                            },
                            success:function(data)
                            {
                                if(data.error.length > 0){
                                    alert(data.error[0]);
                                }
                                if(data.success.length > 0){
                                    alert(data.success[0]);
                                    refresh_Table();
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }

                        });
                    }
                }
                
            }
            
            else if(shift_type == "rdB_IrregularShift"){
                alert("Irregular");
            }
            
            else if(shift_type == "rdB_FlexiShift"){
                alert("Flexi");
            }
            
            else if(shift_type == "rdB_FreeShift"){
                alert("Free");
            }
        }

            // var c = confirm("Apply this Work Schedule Request?");
            // if(c == true)
            // {
            //     var chkIndefinite = document.getElementById("chk_EDIndefinite");chk_RegularShiftTable_days
            //         if(chkIndefinite.checked == true){
            //             var chk_ind = "CHECKED";
            //         }else{
            //             var chk_ind = "NOT CHECK!";
            //         }

            //     var rdB_CustomSchedule  = $("input[type=radio][name=cat_schedule]:checked").val();
            //     var rdB_ScheduleTemplates = $("input[type=radio][name=cat_schedule]:checked").val();
                
            //         if(rdB_CustomSchedule == "rdB_CustomSchedule"){
            //             var chk_sched_custom = "CHECKED";
            //         }else if(rdB_ScheduleTemplates == "rdB_ScheduleTemplates"){
            //             var chk_sched_temp = "CHECKED";
            //         }
                    
            //     var shiftMode = $("input[type=radio][name=shift_list]:checked").val();
            //         if(shiftMode == "rdB_RegularShift"){
            //             var regular_shift_custom = "CHECKED"
            //         }else if(shiftMode == "rdB_IrregularShift"){
            //             var irregular_shift_custom = "CHECKED"
            //         }else if(shiftMode == "rdB_FlexiShift"){
            //             var flexi_shift_custom = "CHECKED"
            //         }else if(shiftMode == "rdB_FreeShift"){
            //             var free_shift_custom = "CHECKED"
            //         }
                
            //     var optradio = $("input[type=radio][name=optradio]:checked").val();
            //     var startDate_CWS = $("#startDate_CWS").val();
            //     var endDate_CWS = $("#endDate_CWS").val();
            //     var txtLocation = $("#txtLocation").val();
            //     var txtReason = $("#txtReason").val();
            //     var dtp_RegularShiftOut = $("#dtp_RegularShiftOut").val();
            //     var dtp_RegularShiftIn = $("#dtp_RegularShiftIn").val();
                

            //     $.ajax({
            //         headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            //         url: "{{ route('saveschedulerequest') }}",
            //         method: "POST",
            //         dataType: "json",
            //         data: {
            //             startDate_CWS:startDate_CWS,
            //             endDate_CWS:endDate_CWS,
            //             chk_ind:chk_ind,
            //             txtLocation:txtLocation,
            //             txtReason:txtReason,
            //             chk_sched_temp:chk_sched_temp,
            //             chk_sched_custom:chk_sched_custom,
            //             regular_shift_custom:regular_shift_custom,
            //             irregular_shift_custom:irregular_shift_custom,
            //             flexi_shift_custom:flexi_shift_custom,
            //             free_shift_custom:free_shift_custom,
            //             optradio:optradio,
            //             dtp_RegularShiftOut:dtp_RegularShiftOut,
            //             dtp_RegularShiftIn:dtp_RegularShiftIn,
            //             chk_RegularShiftTable_days:chk_RegularShiftTable_days,
            //             chk_RegularShiftTable_rest:chk_RegularShiftTable_rest
            //             },
                    
            //         success:function(data)
            //         {
            //             if(data.error.length > 0){
            //                 alert(data.error[0]);
            //             }
            //             if(data.success.length > 0){
            //                 alert(data.success[0]);
            //                 refresh_schedule_list();
            //             }
                        
            //         },
            //         error: function(xhr, ajaxOptions, thrownError){
            //             console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            //         }
            //     });
            // }
        
    });
</script>

<script>
//compute total hours
$(document).ready(function(){
        function computeLunchHours(){

            var l_out = ("7/20/2018 " + $("#addLunchOut").val());
            var l_in = ("7/20/2018 " + $("#addLunchIn").val());
            
            l_out = new Date(l_out);
            l_in = new Date(l_in);

            var t_hours = Math.abs(l_in - l_out) / 36e5;

            if(l_in <= l_out){
                $('#txtLunchHours').val("");
            }
            else{
                $('#txtLunchHours').val(Math.round(t_hours * 100) / 100);
                var l_hrs = $('#txtLunchHours').val();
                $('#hiddenLunchHours').val(l_hrs);
            }
        }

        $("#addLunchIn").focusout(function(){
            if($('#addLunchIn').val() != "" && $('#addLunchOut').val() != ""){
                computeLunchHours();
            }
        });
        $("#addLunchOut").focusout(function(){
            if($('#addLunchIn').val() != "" && $('#addLunchOut').val() != ""){
                computeLunchHours();
            }
        });
    });
    //end-----

</script>



{{-- END DATE HIDE --}}
<script>
    $(document).on("click",function () {
        // var chkIndefinite = $("input[type=radio][name=cat_schedule]:checked").val();
        var chkIndefinite = document.getElementById("chk_EDIndefinite");
        if(chkIndefinite.checked == true)
        {
            $("#irregularEndDate").hide();
        }
        else
        {
            $("#irregularEndDate").show();
        }
    });
</script>

{{-- SCHEDULE MODE --}}
<script>
    $(document).on("click",function () {
    
        var i = $("input[type=radio][name=cat_schedule]:checked").val();

        if(i == "rdB_ScheduleTemplates")
        {
            $('#customSchedule').css('display','none');
            $('#scheduleTemplates').css('display','block');
        }
        else if(i == "rdB_CustomSchedule")
        {
            $('#scheduleTemplates').hide();
            $('#customSchedule').show();
        }
        
    });
</script>

{{-- SHIFT MODES --}}
<script>
    $(document).on("click", function(){
        var shiftMode = $("input[type=radio][name=shift_list]:checked").val();
        if(shiftMode == "rdB_RegularShift")
        {
            $('#flexiShift').hide();
            $('#irregularShift').hide();
            $('#regularShift').show();
        }
        else if(shiftMode == "rdB_IrregularShift")
        {
            $('#flexiShift').hide();
            $('#regularShift').hide();
            $('#irregularShift').show();
        }
        else if(shiftMode == "rdB_FlexiShift")
        {
            $('#irregularShift').hide();
            $('#regularShift').hide();
            $('#flexiShift').show();
        }
        else if(shiftMode == "rdB_FreeShift")
        {
            $('#flexiShift').hide();
            $('#irregularShift').hide();
            $('#regularShift').hide();
        }
    });

</script>

<script>
//regular pick sched day//
        //monday
        $(document).on("click", "#chk_RegularShiftTable_days_mon", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_mon').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_mon').prop("disabled", true); 
                $('#chk_RegularShiftTable_rest_mon').prop("checked", false);  
            }  
        });
        //tuesday
        $(document).on("click", "#chk_RegularShiftTable_days_tue", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_tue').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_tue').prop("disabled", true);  
                $('#chk_RegularShiftTable_rest_tue').prop("checked", false); 
            }  
        });
        //wednesday
        $(document).on("click", "#chk_RegularShiftTable_days_wed", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_wed').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_wed').prop("disabled", true);   
                $('#chk_RegularShiftTable_rest_wed').prop("checked", false);
            }  
        });
        //thursday
        $(document).on("click", "#chk_RegularShiftTable_days_thurs", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_thurs').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_thurs').prop("disabled", true);   
                $('#chk_RegularShiftTable_rest_thurs').prop("checked", false);
            }  
        });
        //friday
        $(document).on("click", "#chk_RegularShiftTable_days_fri", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_fri').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_fri').prop("disabled", true);   
                $('#chk_RegularShiftTable_rest_fri').prop("checked", false);
            }  
        });
        //saturday
        $(document).on("click", "#chk_RegularShiftTable_days_sat", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_sat').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_sat').prop("disabled", true);   
                $('#chk_RegularShiftTable_rest_sat').prop("checked", false);
            }  
        });
        //sunday
        $(document).on("click", "#chk_RegularShiftTable_days_sun", function(){
            if($(this). prop("checked") == true){
                $('#chk_RegularShiftTable_rest_sun').prop("disabled", false);
            }
            else{
                $('#chk_RegularShiftTable_rest_sun').prop("disabled", true);
                $('#chk_RegularShiftTable_rest_sun').prop("checked", false);
            }  
        });
//---------end---------//
</script>

{{-- DATE SETTINGS --}}
<script type="text/javascript">

     $('#table_work_schedule').dataTable({
        "serverSide": false, 
        "retrieve": true, 
        "ordering": false
    });

    $(function () {
        $('#startDate_CWS').datetimepicker({
            format: 'L'
        });
    });

    $(function () {
        $('#endDate_CWS').datetimepicker({
            format: 'L'
        });
    });

    $(function () {
        $('#dtp_RegularShiftIn').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_RegularShiftOut').datetimepicker({
            format: 'LT'
        });
    });
</script>

{{-- DATE SETTING SHIFT --}}
<script>
    $(function () {
        $('#dtp_IrregularShiftIn_mon').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_mon').datetimepicker({
            format: 'LT'
        });
    });


    $(function () {
        $('#dtp_IrregularShiftIn_tue').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_tue').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftIn_wed').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_wed').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftIn_thu').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_thu').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftIn_fri').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_fri').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftIn_sat').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_sat').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftIn_sun').datetimepicker({
            format: 'LT'
        });
    });

    $(function () {
        $('#dtp_IrregularShiftOut_sun').datetimepicker({
            format: 'LT'
        });
    });
    $(function (){
        $('#addLunchIn').datetimepicker({
            format: 'LT'
        });
    });
    $(function (){
        $('#addLunchOut').datetimepicker({
            format: 'LT'
        });
    });


</script>
@endsection