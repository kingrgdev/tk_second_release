@extends('layouts/app')

@section('content')
<style>
    #cardTemplateDetails{
        font-size: 12px;
    }
    #templateCardHeader{
        background: linear-gradient(to top left, #2d79a3 20%, #223373 150%);
        color:white;
    }
    #tableEmployeeList, #tableScheduleRequestRecord, #tableResult, #tableScheduleTemplate, #tableCustomRegularSchedule, #tableCustomIrregSchedule{
        text-align:center;
    }
    #tableEmployeeList th, #tableScheduleRequestRecord th, #tableScheduleTemplate th, #tableCustomRegularSchedule th, #tableCustomRegularSchedule th{
        border-top:1px solid #ddd;
    }
</style>

<div id="pageContainer" class="container-fluid">
    <h1><i class="icon-right fa fa-plus-circle" aria-hidden="true"></i>Add Work Schedule</h1>
    
    <div id="moduleContainer" class="container-fluid">
        <div class=form-group>
            <label class="pull-right"><b>Search Filter </b><span id="searchIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
        </div>
        <br>

        {{-- search field --}}
        <div id="hideSearchField" style="display:none;">
            
            <div class="form-group row">
                <div class="col-md-6" >

                    {{-- lastname --}}
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtLastname" name="txtLastname" type="text" class="form__field" placeholder="Lastname">
                        <label for="txtLastname" class="span-header form__label"><i class="icon-right fa fa-address-card-o" aria-hidden="true"></i>Lastname</label>
                    </div>
                    <br>

                    {{-- company --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbCompany" name="cmbCompany" class="form__field" placeholder="Company">
                            <option value="">Select Company</option>
                        </select>
                        <label for="cmbCompany" class="span-header form__label"><i class="icon-right fa fa-university" aria-hidden="true"></i>Company</label>
                    </div>
                    <br>

                    {{-- team --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbTeam" name="cmbTeam" class="form__field" placeholder="Team">
                            <option value="">Select Team</option>
                        </select>
                        <label for="cmbTeam" class="span-header form__label"><i class="icon-right fa fa-users" aria-hidden="true"></i>Team</label>
                    </div>
                    <br>
                </div>
                <div class="col-md-6">
                    {{-- firstname --}}
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtFirstname" name="txtFirstname" type="text" class="form__field" placeholder="Firstname">
                        <label for="txtFirstname" class="span-header form__label"><i class="icon-right fa fa-address-card-o" aria-hidden="true"></i>Firstname</label>
                    </div>
                    <br>

                    {{-- department --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbDepartment" name="cmbDepartment" class="form__field" placeholder="Department">
                            <option value="">Select Department</option>
                        </select>
                        <span for="cmbDepartment" class="span-header form__label"><i class="icon-right fa fa-university" aria-hidden="true"></i>Department</span>
                    </div>
                    <br>

                    {{-- satatus --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbStatus" name="cmbStatus" class="form__field" placeholder="Status">
                                <option value="">Select Status</option>
                                <option value="Probationary">Probationary</option>
                                <option value="Regular">Regular</option>
                                <option value="Project Based">Project Based</option>
                        </select>
                        <label for="cmbStatus" class="span-header form__label"><i class="icon-right fa fa-bar-chart" aria-hidden="true"></i>Status</label>
                    </div>

                    <div class="container">
                        <div class="form-group row">
                            <div class="form__group col-md-8 fg_margin">
                                <input type="submit" id="btnSearch" name="btnSearch" class="btn btn-sm button blue" value="Search" style="width:100px;">
                                <input type="submit" id="btnClear" name="btnClear" class="btn btn-sm button red" value="Clear" style="width:100px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end search field --}}

        {{-- work schedule tabs --}}
        <ul class="nav nav-tabs" id="workScheduleTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="btnTabWorkSchedule" data-toggle="tab" href="#tabWorkSchedule" role="tab" aria-controls="btnTabWorkSchedule" aria-selected="true">Work Schedule</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="btnTabWorkScheduleRecord" data-toggle="tab" href="#tabWorkScheduleRecord" role="tab" aria-controls="btnTabWorkScheduleRecord" aria-selected="false">Work Schedule Records</a>
            </li>
        </ul>

        <form id="formWorkSchedule">
            <div class="tab-content" id="myTabContent">
                {{-- tab work schedule --}}
                <div class="tab-pane fade show active" id="tabWorkSchedule" role="tabpanel" aria-labelledby="workschedule-tab">
                    <div class=form-group>
                        <label class="pull-right"><b>Work Schedule Field </b><span id="workScheduleIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
                    </div>
                    <br><br>

                    {{-- apply work schedule --}}
                    <div id="hideWorkScheduleField" style="display:none">

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="form__group col-md-8 fg_margin">
                                    <select id="cmbScheduleType" name="cmbScheduleType" class="form__field">
                                        <option value="">Select Schedule</option>
                                    </select>
                                    <label for="cmbScheduleType" class="span-header form__label"><i class="icon-right fa fa-server" aria-hidden="true"></i>Schedule Type</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="card" style="width:auto;">
                                    <div id="templateCardHeader"class="card-header"><b>Template Details</b></div>
                                    <div id="cardTemplateDetails" class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                {{-- schedule type --}}
                                                <div class="form-group row">
                                                    <label class="col"for='scheduleType'><b>Schedule Type :</b></label>
                                                    <label id="scheduleType" class="col">--</label>
                                                </div>
                                                {{-- lunch out --}}
                                                <div class="form-group row">
                                                    <label class="col"for='lunchOut'><b>Lunch Out :</b></label>
                                                    <label id="lunchOut" class="col">--</label>
                                                </div>
                                                {{-- lunch in --}}
                                                <div class="form-group row">
                                                    <label class="col"for='lunchIn'><b>Lunch In :</b></label>
                                                    <label id="lunchIn" class="col">--</label>
                                                </div>
                                                {{-- monday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='monday'><b>Monday :</b></label>
                                                    <label id="monday" class="col">--</label>
                                                </div>
                                                {{-- tuesday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='tuesday'><b>Tuesday :</b></label>
                                                    <label id="tuesday" class="col">--</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {{-- wednesday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='wednesday'><b>Wednesday :</b></label>
                                                    <label id="wednesday" class="col">--</label>
                                                </div>
                                                {{-- thursday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='thursday'><b>Thursday :</b></label>
                                                    <label id="thursday" class="col">--</label>
                                                </div>
                                                {{-- friday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='friday'><b>Friday :</b></label>
                                                    <label id="friday" class="col">--</label>
                                                </div>
                                                {{-- saturday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='saturday'><b>Saturday :</b></label>
                                                    <label id="saturday" class="col">--</label>
                                                </div>
                                                {{-- sunday --}}
                                                <div class="form-group row">
                                                    <label class="col"for='sunday'><b>Sunday :</b></label>
                                                    <label id="sunday" class="col">--</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- start date --}}
                                <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                                    <input id="startDate" name="startDate" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#startDate" data-toggle="datetimepicker">
                                    <label for="startDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>Start Date</label>
                                </div>
                                <div style="margin-bottom:15px"></div>

                                {{-- end date --}}
                                <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                                    <input id="endDate" name="endDate" class="datetimepicker-input form__field" placeholder="End Date" data-target="#endDate" data-toggle="datetimepicker">
                                    <label for="endDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>End Date</label>
                                </div>

                                <br>
                                <div class="form__group col-md-8 fg_margin">
                                    <div class="container form-group row">
                                        <div class="form-group" style="margin-right:7px;">
                                            {{-- apply work schedule --}}
                                            <input id="btnApply" type="button" class="btn btn-sm button blue" value="Apply Schedule Request"/>
                                        </div>
                                        <div class="form-group" style="margin-right:7px;">
                                            {{-- add work schedukle --}}
                                            <input id="btnAdd" type="button" class="btn btn-sm button blue" value="Add Schedule"/>
                                        </div>
                                        <div class="form-group">
                                            <input id="selectCount" name="selectCount" type="hidden" value=""/>
                                            <div class="custom-control custom-checkbox">
                                                <input id="checkAll" name="checkAll" type="checkbox" class="custom-control-input"/>
                                                <label class="custom-control-label" for="checkAll"><b>Check All</b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    {{-- work schedule status --}}
                    <div id="workScheduleStatus" class="row text-center" style="display:none;">
                        <div class="col-sm-12">                         
                            <input id="btnFail" name="btnFail" type="button" class="btn btn-sm button red" value="0 failed" style=" font-weight: bold">    
                            <input id="btnSuccess" name="btnSuccess" type="button" class="btn btn-sm button green" value="0 success" style=" font-weight: bold">         
                        </div>
                    </div>

                    {{-- table work schedule list --}}
                    <div id="divEmployeeList" class="table-responsive">
                        <table id="tableEmployeeList" name="tableEmployeeList" class="table table-hover" cellspacing="0" style="width:100%" >
                            <thead>
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
                                </tr>    
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="tabWorkScheduleRecord" role="tabpanel" aria-labelledby="workScheduleRecord-tab">
                    <br>
                        {{-- table record --}}
                        <div id="divScheduleRequestRecord" class="table-responsive">
                            <table id="tableScheduleRequestRecord" name="tableScheduleRequestRecord" class="table table-hover" cellspacing="0" style="width:100%">
                                <thead>
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- modal fail/success --}}
<div id="modalResult" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title" ><b></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="divResult" class="table-responsive">
                    <table id="tableResult" name="tableResult" class="table table-hover" cellspacing="0" style="width:100%" >
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="bodyFail">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>    
                        </tbody>
                        <tbody id="bodySuccess">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>    
                        </tbody>
                    </table>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal add schedule -->
<div id="modalAddSchedule" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title" ><b>Add Schedule</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id=formScheduleTemplate>
                    <!-- template name -->
                    <div class="form__group col-md-8">
                        <input id="txtTemplateName" name="txtTemplateName" type="text" class="form__field" placeholder="Template Name">
                        <label for="txtTemplateName" class="span-header form__label"><i class="icon-right fa fa-file-text-o" aria-hidden="true"></i>Template Name</label>
                    </div>
                    <br>
                    <!-- end -->

                    <!-- schedule description -->
                    <div class="form__group col-md-8">
                        <textarea id="txtScheduleDesc" name="txtScheduleDesc" type="text"  class="form__field" rows="3" placeholder="Schedule Description"></textarea>
                        <label for="txtScheduleDesc" class="span-header form__label"><i class="icon-right fa fa-pencil" aria-hidden="true"></i>Schedule Description</label>
                    </div>
                    <!-- end -->

                    <div class="container">
                        <div class="form-group row">
                            <!-- lunch out -->
                            <div class="form__group col-md-2 fg_margin input-group date my-2" data-target-input="nearest">
                                <input id="addLunchOut" name="addLunchOut" type="text" class="datetimepicker-input form__field" data-target="#addLunchOut" data-toggle="datetimepicker" placeholder="Lunch Out">
                                <label for="addLunchOut" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch Out</label>
                            </div>
                            <!-- end -->
                            
                            <!-- lunch in -->
                            <div class="form__group col-md-2 fg_margin input-group date my-2" data-target-input="nearest">
                                <input id="addLunchIn" name="addLunchIn" type="text" class="datetimepicker-input form__field" data-target="#addLunchIn" data-toggle="datetimepicker" placeholder="Lunch In">
                                <label for="addLunchIn" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch In</label>
                            </div>
                            <!-- end -->
                            
                            <!-- total hours -->
                            <input id="hiddenLunchHours" name="hiddenLunchHours" type="hidden">
                            <div class="form__group col-md-2 fg_margin my-2">
                                <input id="txtLunchHours" name="txtLunchHours" type="number" class="form__field" placeholder="Lunch Hours" disabled>
                                <label for="txtLunchHours" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Lunch Hours</label>
                            </div>
                            <!-- end -->
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="form-inline">

                            <!-- regular -->
                            <div class="custom-control custom-radio">
                                <input id="rdRegularShift" name="rdShiftType" type="radio" class="custom-control-input rdShift" value="Regular Shift" checked/>
                                <label class="custom-control-label" for="rdRegularShift"><strong>Regular Shift</strong></label>
                            </div>

                            <!-- irregular -->
                            <div class="custom-control custom-radio">
                                <input id="rdIrregularShift" name="rdShiftType" type="radio" class="custom-control-input rdShift" value="Irregular Shift"/>
                                <label class="custom-control-label" for="rdIrregularShift"><strong>Irregular Shift</strong></label>
                            </div>
                            
                            <!-- flexi -->
                            <div class="custom-control custom-radio">
                                <input id="rdFlexiShift" name="rdShiftType" type="radio" class="custom-control-input rdShift" value="Flexi Shift"/>
                                <label class="custom-control-label" for="rdFlexiShift"><strong>Flexi Shift</strong></label>
                            </div>
                            
                            <!-- free -->
                            <div class="custom-control custom-radio">
                                <input id="rdFreeShift" name="rdShiftType" type="radio" class="custom-control-input rdShift" value="Free Shift"/>
                                <label class="custom-control-label" for="rdFreeShift"><strong>Free Shift</strong></label>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- div regular shift -->
                    <div id="divRegularShift" style="display:none">
                        <h1>Regular</h1>
                        <br>
                        <div class="container">
                            <div class="form group row">
                                <div class="form__group col-md-5 fg_margin input-group date" data-target-input="nearest">
                                    <input type="text" id="regShiftIn" name = "regShiftIn" class="datetimepicker-input form__field" placeholder="Shift In" data-target="#regShiftIn" data-toggle="datetimepicker">
                                    <label for="regShiftIn" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Shift In</label>
                                </div>

                                <div class="form__group col-md-5 fg_margin input-group date" data-target-input="nearest">
                                    <input type="text" id="regShiftOut" name="regShiftOut" class="datetimepicker-input form__field" placeholder="Shift Out" data-target="#regShiftOut" data-toggle="datetimepicker">
                                    <label for="regShiftOut" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Shift Out</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        {{-- table regular schedule --}}
                        <div id="divCustomRegularSchedule" class="table-responsive">
                            <table id="tableCustomRegularSchedule" name="tableCustomRegularSchedule" class="table table-hover" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Days</th>
                                        <th>Is Working Rest Day?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- monday --}}
                                    <tr>    
                                        <td><input id="chkRegularMon" name="chkRegularMon" type="checkbox"></td>
                                        <td><label>Monday</label><br></td>
                                        <td><input id="chkRegularRestDayMon" type="checkbox" name="chkRegularRestDayMon" disabled></td>
                                    </tr>
                                    {{-- tuesday --}}
                                    <tr>    
                                        <td><input id="chkRegularTue" name="chkRegularTue" type="checkbox"></td>
                                        <td><label>Tuesday</label><br></td>
                                        <td><input id="chkRegularRestDayTue" type="checkbox" name="chkRegularRestDayTue" disabled></td>
                                    </tr>
                                    {{-- wednesday --}}
                                    <tr>    
                                        <td><input id="chkRegularWed" name="chkRegularWed" type="checkbox"></td>
                                        <td><label>Wednesday</label><br></td>
                                        <td><input id="chkRegularRestDayWed" type="checkbox" name="chkRegularRestDayWed" disabled></td>
                                    </tr>
                                    {{-- thursday --}}
                                    <tr>    
                                        <td><input id="chkRegularThu" name="chkRegularThu" type="checkbox" ></td>
                                        <td><label>Thursday</label><br></td>
                                        <td><input id="chkRegularRestDayThu" type="checkbox" name="chkRegularRestDayThu" disabled></td>
                                    </tr>
                                    {{-- friday --}}
                                    <tr>    
                                        <td><input id="chkRegularFri" name="chkRegularFri" type="checkbox"></td>
                                        <td><label>Friday</label><br></td>
                                        <td><input id="chkRegularRestDayFri" type="checkbox" name="chkRegularRestDayFri" disabled></td>
                                    </tr>
                                    {{-- saturday --}}
                                    <tr>    
                                        <td><input id="chkRegularSat" name="chkRegularSat" type="checkbox"></td>
                                        <td><label>Saturday</label><br></td>
                                        <td><input id="chkRegularRestDaySat" type="checkbox" name="chkRegularRestDaySat" disabled></td>
                                    </tr>
                                    {{-- sunday --}}
                                    <tr>    
                                        <td><input id="chkRegularSun" name="chkRegularSun" type="checkbox"></td>
                                        <td><label>Sunday</label><br></td>
                                        <td><input id="chkRegularRestDaySun" type="checkbox" name="chkRegularRestDaySun" disabled></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- div irregular -->
                    <div id="divIrregularShift" style="display:none">
                        <h1>Irregular</h1>
                        <br>

                        <div id="divCustomIrregSchedule" class="table-responsive">
                            <table id="tableCustomIrregSchedule" name="tableCustomIrregSchedule" class="table table-hover" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr class="header" style="background:#f7f7f7;">
                                        <th colspan="10" class="text-center">LEAVED RECORDS</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Days</th>
                                        <th>Is Working Rest Day?</th>
                                        <th>Shift In</th>
                                        <th>Shift Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- monday --}}
                                    <tr>    
                                        <td><input id="chkIrregMon" name="chkIrregMon" type="checkbox"></td>
                                        <td><label>Monday</label><br></td>
                                        <td><input id="chkIrregRestDayMon" type="checkbox" name="chkIrregRestDayMon" disabled></td>
                                        <td><input name="monIrregTimeIn" id="monIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="monIrregTimeOut" id="monIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- tuesday --}}
                                    <tr>    
                                        <td><input id="chkIrregTue" name="chkIrregTue" type="checkbox"></td>
                                        <td><label>Tuesday</label><br></td>
                                        <td><input id="chkIrregRestDayTue" type="checkbox" name="chkIrregRestDayTue" disabled></td>
                                        <td><input name="tueIrregTimeIn" id="tueIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="tueIrregTimeOut" id="tueIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- wednesday --}}
                                    <tr>    
                                        <td><input id="chkIrregWed" name="chkIrregWed" type="checkbox"></td>
                                        <td><label>Wednesday</label><br></td>
                                        <td><input id="chkIrregRestDayWed" type="checkbox" name="chkIrregRestDayWed" disabled></td>
                                        <td><input name="wedIrregTimeIn" id="wedIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="wedIrregTimeOut" id="wedIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- thursday --}}
                                    <tr>    
                                        <td><input id="chkIrregThu" name="chkIrregThu" type="checkbox"></td>
                                        <td><label>Thursday</label><br></td>
                                        <td><input id="chkIrregRestDayThu" type="checkbox" name="chkIrregRestDayThu" disabled></td>
                                        <td><input name="thuIrregTimeIn" id="thuIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="thuIrregTimeOut" id="thuIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- friday --}}
                                    <tr>    
                                        <td><input id="chkIrregFri" name="chkIrregFri" type="checkbox"></td>
                                        <td><label>Friday</label><br></td>
                                        <td><input id="chkIrregRestDayFri" type="checkbox" name="chkIrregRestDayFri" disabled></td>
                                        <td><input name="friIrregTimeIn" id="friIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="friIrregTimeOut" id="friIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- saturday --}}
                                    <tr>    
                                        <td><input id="chkIrregSat" name="chkIrregSat" type="checkbox"></td>
                                        <td><label>Saturday</label><br></td>
                                        <td><input id="chkIrregRestDaySat" type="checkbox" name="chkIrregRestDaySat" disabled></td>
                                        <td><input name="satIrregTimeIn" id="satIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="satIrregTimeOut" id="satIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                    {{-- sunday --}}
                                    <tr>    
                                        <td><input id="chkIrregSun" name="chkIrregSun" type="checkbox"></td>
                                        <td><label>Sunday</label><br></td>
                                        <td><input id="chkIrregRestDaySun" type="checkbox" name="chkIrregRestDaySun" disabled></td>
                                        <td><input name="sunIrregTimeIn" id="sunIrregTimeIn" type="time" value="" class='form-control datetime' disabled></td>
                                        <td><input name="sunIrregTimeOut" id="sunIrregTimeOut" type="time" value="" class='form-control datetime' disabled></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- div flexi -->
                    <div id="divFlexiShift" style="display:none">
                        <h1>Flexi</h1>
                        <br>
                        <div class="form-group">
                            <div class="form-inline">
                                <!-- flexi monday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiMon" name="chkFlexiMon" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiMon"><b>Monday</b></label>
                                </div>
                                <!-- flexi tuesday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiTue" name="chkFlexiTue" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiTue"><b>Tuesday</b></label>
                                </div>
                                <!-- flexi wednesday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiWed" name="chkFlexiWed" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiWed"><b>Wednesday</b></label>
                                </div>
                                <!-- flexi thursday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiThu" name="chkFlexiThu" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiThu"><b>Thursday</b></label>
                                </div>
                                <!-- flexi friday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiFri" name="chkFlexiFri" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiFri"><b>Friday</b></label>
                                </div>
                                <!-- flexi saturday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiSat" name="chkFlexiSat" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiSat"><b>Saturday</b></label>
                                </div>
                                <!-- flexi sunday -->
                                <div class="custom-control custom-checkbox">
                                    <input id="chkFlexiSun" name="chkFlexiSun" type="checkbox" class="custom-control-input"/>
                                    <label class="custom-control-label" for="chkFlexiSun"><b>Sunday</b></label>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom:30px;"></div>
                        <!--hours to complete -->
                        <div class="form__group col-md-3">
                            <input id="txtHoursDuration" name="txtHoursDuration" type="number" class="form__field" placeholder="Hours To Complete">
                            <label for="txtHoursDuration" class="span-header form__label"><i class="icon-right fa fa-clock-o" aria-hidden="true"></i>Hours To Complete</label>
                        </div>
                    </div>
                    <div id="divFreeShift" style="display:none">
                        <h1>Free</h1>
                        <br>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
                <button id="btnSave" class="btn btn-sm button blue" type="button" style="width:70px;">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->
<script>
//get employee info and get overtime record
refreshTable();
    function refreshTable(){

        // refresh employee list
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getemployeeschedule') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divEmployeeList').html(data);
                $('#tableEmployeeList').dataTable({

                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                }); 
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        //refresh overtime record
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getschedulerequestrecord') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divScheduleRequestRecord').html(data);
                $('#tableScheduleRequestRecord').dataTable({

                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                }); 
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    //get company
    $(document).ready(function(){
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getcompany') }}",
            method: "GET",
            data:{}, 
            success:function(data){

                $("#cmbCompany").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end get company

    //get department
    $(document).ready(function(){
        $("#cmbCompany").change(function(){
            var ind = $(this).val();
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('getdepartment') }}",
                method: "GET",
                data:{ind:ind}, 
                success:function(data){

                    $("#cmbDepartment").html(data);
                    $('#cmbDepartment').trigger("change");
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //end get department

    //get team
    $(document).ready(function(){
        $("#cmbDepartment").change(function(){

            var department = $(this).val();

            var deptInfo = department.split("]]");
            var deptval = deptInfo[0];

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('getteam') }}",
                method: "GET",
                data:{deptind:deptval},
                success:function(data){
                    $("#cmbTeam").html(data);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //end get team

    //get schedule tpye
    $(document).ready(function(){
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getscheduletype') }}",
            method: "GET",
            data:{},
            success:function(data){
                $("#cmbScheduleType").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    //end get schedule type
        $("#cmbScheduleType").change(function(){

            var id = $(this).val();

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('getschedule') }}",
                method: "GET",
                dataType: "json",
                data:{ind:id},
                success:function(data)
                {
                    $("#scheduleType").html(data.sched_type);
                    $("#lunchOut").html(data.lunch_out);
                    $("#lunchIn").html(data.lunch_in);
                    $("#monday").html(data.mon);
                    $("#tuesday").html(data.tue);
                    $("#wednesday").html(data.wed);
                    $("#thursday").html(data.thu);
                    $("#friday").html(data.fri);
                    $("#saturday").html(data.sat);
                    $("#sunday").html(data.sun);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    }); 

    //search employee
    $(document).on("click", "#btnSearch", function(){

        var lname = $('#txtLastname').val();
        var fname = $('#txtFirstname').val();

        var companyind = $('#cmbCompany').val();

        var department = $("#cmbDepartment").val();
        var deptInfo = department.split("]]");
        var deptval = deptInfo[1];

        var team = $('#cmbTeam').val();
        var status = $('#cmbStatus').val();

        //leave employee list table
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchemployeeschedule') }}",
            method: "GET",
            data:{lastname:lname, firstname:fname, company:companyind, department:deptval, team:team, status:status}, 
            success:function(data){
                $("#divEmployeeList").html(data);
                $('#tableEmployeeList').dataTable({
                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                });  
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        }); 

        // search schedule records
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchemployeeschedulerecordrequest') }}",
            method: "GET",
            data:{lastname:lname, firstname:fname, company:companyind, department:deptval, team:team, status:status}, 
            success:function(data){
                $("#divScheduleRequestRecord").html(data);
                $('#tableScheduleRequestRecord').dataTable({
                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                });  
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end search employee

    //modal fail view
    $(document).on("click", "#btnAdd", function(){
        
        $('#modalAddSchedule').modal('show');
        
    });

    //modal fail view
    $(document).on("click", "#btnFail", function(){
        
        $('#modalResult').modal('show');
        $('#bodyFail').show();
        $('#bodySuccess').hide();
        $('#modalTitle').html('Failed Leave');
        document.getElementById("tableResult").style.borderTop = "7px solid #DD4B39";
        
    });

    //modal success view
    $(document).on("click", "#btnSuccess", function(){
        
        $('#modalResult').modal('show');
        $('#bodySuccess').show();
        $('#bodyFail').hide();
        $('#modalTitle').html('Success Leave');
        document.getElementById("tableResult").style.borderTop = "7px solid #00A65A";
    });

    //clear field
    $(document).on("click", "#btnClear", function(){

        $('#txtLastname').val("");
        $('#txtFirstname').val("");
        $('#cmbCompany').val("");
        $('#cmbDepartment').val("");
        $('#cmbTeam').val("");
        $('#cmbStatus').val("");
    });

    //add leave
    $(document).on("click", "#btnApply", function(){

        var a = $('select[name=tableEmployeeList_length]').val();
        checkSelect();
        //check leave
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkschedulerequest') }}",
            method: "GET",
            data:$('#formWorkSchedule').serialize(),
            dataType: "json", 
            success:function(data){

                if(data.error.length > 0){

                    $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                    $('select[name=tableEmployeeList_length]').val(a).trigger('change');

                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want add schedule to the user(s)?") == true){

                        $('#btnSuccess').val("0 success");
                        $('#btnFail').val("0 failed");
                        $("#bodySuccess").html("");
                        $("#bodyFail").html("");

                        var res_success = "";
                        var res_fail = "";
                        
                        //add schedule
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('addschedulerequest') }}",
                            method: "POST",
                            data:$('#formWorkSchedule').serialize(),
                            dataType: "json", 
                            success:function(data){
                                if(data.success.length > 0){
                                    for(var count = 0;count < data.success.length; count++){

                                        $('#btnSuccess').val(data.success.length + " success")
                                        var suc = data.success[count].split("]]");
                                        res_success += "<tr><td>"+ suc[0] +"</td>"; 
                                        res_success += "<td>"+ suc[1] +"</td>"; 
                                        res_success += "<td>"+ suc[2] +"</td></tr>"; 
                                        
                                        $("#bodySuccess").html(res_success);
                                        $('#tableResult').dataTable({
                                            "serverSide": false, 
                                            "retrieve": true, 
                                            "ordering": false,
                                        });
                                    }
                                }
                                $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                                $('select[name=tableEmployeeList_length]').val(a).trigger('change');

                                alert("Work Schedule Process Complete!");
                                refreshTable();
                                $('#workScheduleStatus').css("display", "block");
                                $('#checkAll').prop("checked", false);
                                checkAll();
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
                    else{

                        $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                        $('select[name=tableEmployeeList_length]').val(a).trigger('change');
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end add schedule request

    //cancel overtime
    $(document).on("click", ".btnCancel", function(){

        var id = $(this).data("add");
        if(confirm("Are you sure you want to cancel schedule request to this employee?") == true){

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('cancelschedulerequest') }}",
                method: "POST",
                data: {id:id}, 
                success:function(data)
                {
                    alert("Schedule Request successfully cancel!");
                    refreshTable();
                }
            }); 
        }
    });
    //end cancel overtime

    // add schedule
    $(document).on("click", "#btnSave", function(){
        //check leave
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkscheduletemplate') }}",
            method: "GET",
            data:$('#formScheduleTemplate').serialize(),
            dataType: "json", 
            success:function(data){
                if(data.error.length > 0){

                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want add this schedule?") == true){
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('addscheduletemplate') }}",
                            method: "POST",
                            data:$('#formScheduleTemplate').serialize(),
                            success:function(data){
                                alert("Schedule successfully added!");
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end

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

    //choose schedule template or custom template
    $(document).ready(function(){

        shiftType();

        //shift type
        $(document).on("click", ".rdShift", function(){
            shiftType();
        });
    //regular pick sched day
        //monday
        $(document).on("click", "#chkRegularMon", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDayMon').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDayMon').prop("disabled", true); 
                $('#chkRegularRestDayMon').prop("checked", false);  
            }  
        });
        //tuesday
        $(document).on("click", "#chkRegularTue", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDayTue').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDayTue').prop("disabled", true);  
                $('#chkRegularRestDayTue').prop("checked", false); 
            }  
        });
        //wednesday
        $(document).on("click", "#chkRegularWed", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDayWed').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDayWed').prop("disabled", true);   
                $('#chkRegularRestDayWed').prop("checked", false);
            }  
        });
        //thursday
        $(document).on("click", "#chkRegularThu", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDayThu').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDayThu').prop("disabled", true);   
                $('#chkRegularRestDayThu').prop("checked", false);
            }  
        });
        //friday
        $(document).on("click", "#chkRegularFri", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDayFri').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDayFri').prop("disabled", true);   
                $('#chkRegularRestDayFri').prop("checked", false);
            }  
        });
        //saturday
        $(document).on("click", "#chkRegularSat", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDaySat').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDaySat').prop("disabled", true);   
                $('#chkRegularRestDaySat').prop("checked", false);
            }  
        });
        //sunday
        $(document).on("click", "#chkRegularSun", function(){
            if($(this). prop("checked") == true){
                $('#chkRegularRestDaySun').prop("disabled", false);
            }
            else{
                $('#chkRegularRestDaySun').prop("disabled", true);
                $('#chkRegularRestDaySun').prop("checked", false);
            }  
        });
    //end-----------------

    //irregular pick sched day
        //monday
        $(document).on("click", "#chkIrregMon", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDayMon').prop("disabled", false);
                $('#monIrregTimeIn').prop("disabled", false);
                $('#monIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDayMon').prop("disabled", true);
                $('#chkIrregRestDayMon').prop("checked", false); 
                $('#monIrregTimeIn').val("");
                $('#monIrregTimeOut').val("");
                $('#monIrregTimeIn').prop("disabled", true);
                $('#monIrregTimeOut').prop("disabled", true);
                 
            }  
        });
        //tuesday
        $(document).on("click", "#chkIrregTue", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDayTue').prop("disabled", false);
                $('#tueIrregTimeIn').prop("disabled", false);
                $('#tueIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDayTue').prop("disabled", true);  
                $('#chkIrregRestDayTue').prop("checked", false); 
                $('#tueIrregTimeIn').val("");
                $('#tueIrregTimeOut').val("");
                $('#tueIrregTimeIn').prop("disabled", true);
                $('#tueIrregTimeOut').prop("disabled", true);
            }  
        });
        //wednesday
        $(document).on("click", "#chkIrregWed", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDayWed').prop("disabled", false);
                $('#wedIrregTimeIn').prop("disabled", false);
                $('#wedIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDayWed').prop("disabled", true);   
                $('#chkIrregRestDayWed').prop("checked", false);
                $('#wedIrregTimeIn').val("");
                $('#wedIrregTimeOut').val("");
                $('#wedIrregTimeIn').prop("disabled", true);
                $('#wedIrregTimeOut').prop("disabled", true);
            }  
        });
        //thursday
        $(document).on("click", "#chkIrregThu", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDayThu').prop("disabled", false);
                $('#thuIrregTimeIn').prop("disabled", false);
                $('#thuIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDayThu').prop("disabled", true);   
                $('#chkIrregRestDayThu').prop("checked", false);
                $('#thuIrregTimeIn').val("");
                $('#thuIrregTimeOut').val("");
                $('#thuIrregTimeIn').prop("disabled", true);
                $('#thuIrregTimeOut').prop("disabled", true);
            }  
        });
        //friday
        $(document).on("click", "#chkIrregFri", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDayFri').prop("disabled", false);
                $('#friIrregTimeIn').prop("disabled", false);
                $('#friIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDayFri').prop("disabled", true);   
                $('#chkIrregRestDayFri').prop("checked", false);
                $('#friIrregTimeIn').val("");
                $('#friIrregTimeOut').val("");
                $('#friIrregTimeIn').prop("disabled", true);
                $('#friIrregTimeOut').prop("disabled", true);
            }  
        });
        //saturday
        $(document).on("click", "#chkIrregSat", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDaySat').prop("disabled", false);
                $('#satIrregTimeIn').prop("disabled", false);
                $('#satIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDaySat').prop("disabled", true);   
                $('#chkIrregRestDaySat').prop("checked", false);
                $('#satIrregTimeIn').val("");
                $('#satIrregTimeOut').val("");
                $('#satIrregTimeIn').prop("disabled", true);
                $('#satIrregTimeOut').prop("disabled", true);
            }  
        });
        //sunday
        $(document).on("click", "#chkIrregSun", function(){
            if($(this). prop("checked") == true){
                $('#chkIrregRestDaySun').prop("disabled", false);
                $('#sunIrregTimeIn').prop("disabled", false);
                $('#sunIrregTimeOut').prop("disabled", false);
            }
            else{
                $('#chkIrregRestDaySun').prop("disabled", true);
                $('#chkIrregRestDaySun').prop("checked", false);
                $('#sunIrregTimeIn').val("");
                $('#sunIrregTimeOut').val("");
                $('#sunIrregTimeIn').prop("disabled", true);
                $('#sunIrregTimeOut').prop("disabled", true);
            }  
        });
    //end-----------------
    });

    //choose shift type
    function shiftType(){
        var shift = $("input[type=radio][name=rdShiftType]:checked").val();  

        if(shift == "Regular Shift"){

            $('#divIrregularShift').css('display','none');
            $('#divFlexiShift').css('display','none');
            $('#divFreeShift').css('display','none');
            $('#divRegularShift').css('display','block');
        }
        else if(shift == "Irregular Shift"){
 
            $('#divFlexiShift').css('display','none');
            $('#divFreeShift').css('display','none');
            $('#divRegularShift').css('display','none');
            $('#divIrregularShift').css('display','block');
        }
        else if(shift == "Flexi Shift"){

            $('#divFreeShift').css('display','none');
            $('#divRegularShift').css('display','none');
            $('#divIrregularShift').css('display','none');
            $('#divFlexiShift').css('display','block');
        }
        else if(shift == "Free Shift"){

            $('#divRegularShift').css('display','none');
            $('#divIrregularShift').css('display','none');
            $('#divFlexiShift').css('display','none');
            $('#divFreeShift').css('display','block');
        }
    }
    //end

    // check if it has selected employee
    function checkSelect()
    {
        var a = $('select[name=tableEmployeeList_length]').val();
        $('select[name=tableEmployeeList_length]').append("<option id='optioncount' value='"+ $('#tblCount').val() +"'></option>");
        $('select[name=tableEmployeeList_length]').val( $('#tblCount').val() ).trigger('change');

        getCount = 0;
        var count = $('#tblCount').val();

        for(i = 1; i <=count; i++){
            if($('#empList' + i)){
                if($('#empList' + i).prop("checked") == true){

                    getCount++;
                }
            }
        }
        $('#selectCount').val(getCount);
    }

    //check all
    $(document).ready(function(){
        $(document).on("click", "#checkAll", function(){ // check all employee on apply settings

            checkAll();
        });
    });

    function checkAll()
    {
        var a = $('select[name=tableEmployeeList_length]').val();
        $('select[name=tableEmployeeList_length]').append("<option id='optioncount' value='" + $('#tblCount').val() + "'></option>");
        $('select[name=tableEmployeeList_length]').val($('#tblCount').val()).trigger('change');

        if($("#checkAll").prop("checked") == true){   

            for (i = 1; i <= $('#tblCount').val(); i++) { 
                $("#empList" + i).prop("checked", true);
            }

            $("select[name=tableEmployeeList_length] option[value='" + $('#tblCount').val() + "']").remove(); 
            $('select[name=tableEmployeeList_length]').val(a).trigger('change');
        }
        else{

            for (i = 1; i <= $('#tblCount').val(); i++) { 
                $("#empList" + i).prop("checked", false);
            } 

            $("select[name=tableEmployeeList_length] option[value='" + $('#tblCount').val() + "']").remove(); 
            $('select[name=tableEmployeeList_length]').val(a).trigger('change');
        }
    }

    //search field
    $(document).on("click", "#searchIcon", function(){
        if($("#hideSearchField").is(":visible")){

            $("#searchIcon").removeClass("fa-caret-square-o-down");
            $("#searchIcon").addClass("fa fa-caret-square-o-right");
            $("#hideSearchField").stop().slideUp(150);
        }
        else{

            $("#searchIcon").removeClass("fa-caret-square-o-right");
            $("#searchIcon").addClass("fa-caret-square-o-down");
            $("#hideSearchField").stop().slideDown(150);
            
        }
    });

    //work schedule field
    $(document).on("click", "#workScheduleIcon", function(){
        if($("#hideWorkScheduleField").is(":visible")){

            $("#workScheduleIcon").removeClass("fa-caret-square-o-down");
            $("#workScheduleIcon").addClass("fa fa-caret-square-o-right");
            $("#hideWorkScheduleField").stop().slideUp(150);
        }
        else{

            $("#workScheduleIcon").removeClass("fa-caret-square-o-right");
            $("#workScheduleIcon").addClass("fa-caret-square-o-down");
            $("#hideWorkScheduleField").stop().slideDown(150);
            
        }
    });

    $(function (){
        $('#startDate').datetimepicker({
            format: 'L'
        });
    });

    $(function (){
        $('#endDate').datetimepicker({
            format: 'L'
        });
    });

        $(function (){
        $('#mStartDate').datetimepicker({
            format: 'L'
        });
    });

    $(function (){
        $('#mEndDate').datetimepicker({
            format: 'L'
        });
    });
    $(function (){
        $('#regShiftIn').datetimepicker({
            format: 'LT'
        });
    });
    $(function (){
        $('#regShiftOut').datetimepicker({
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