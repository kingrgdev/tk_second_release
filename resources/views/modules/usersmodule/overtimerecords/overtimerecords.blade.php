@extends('layouts/app')

@section('content')
<style>
#tableOvertimeRecord{
    text-align:center;
}
#tableOvertimeRecord th{
    border-bottom:1px solid #000;
    /* border-top:1px solid #000; */
}

</style>

@php
if(Session::get('overtime_records') == 'all'){
    $add = '';
    $edit = '';
    $delete = '';
}
elseif(Session::get('overtime_records') == 'view'){
    $add = 'disabled';
    $edit = 'disabled';
    $delete = 'disabled';
}
elseif(Session::get('overtime_records') == 'add'){
    $add = '';
    $edit = 'disabled';
    $delete = 'disabled';
}
elseif(Session::get('overtime_records') == 'edit'){
    $add = '';
    $edit = '';
    $delete = 'disabled';
}
elseif(Session::get('overtime_records') == 'delete'){
    $add = '';
    $edit = 'disabled';
    $delete = '';
}else{
    $add = 'disabled';
    $edit = 'disabled';
    $delete = 'disabled';
}                   
@endphp

<div id="pageContainer" class="container-fluid">
    <h1>Overtime Records</h1>

    @include('modules.usersmodule.userprofile.user_profile')

    <div id="moduleContainer" class="container-fluid" style="padding:9px;">
        <br>
        <div class="form-group">
            <div class="col-md-3">
                <div class="row">
                    <div class="form__group col-md-6 fg_margin">
                        <select id="cmbSearchBy" name="cmbSearchBy" class="form__field">
                            <option value="All">All</option>
                            <option value="Date Range">Date Range</option>
                            <option value="Status">Status</option>
                        </select>
                        <label for="cmbSearchBy" class="span-header form__label"><i class="icon-right fa fa-search" aria-hidden="true"></i>Search By</label>
                    </div>
                    <div class="form__group col-md-5 fg_margin">
                        <input id="btnAdd" name="btnAdd" class="btn btn-sm button blue" type="button" value="Generate" style="width:100px;"/>
                    </div>
                </div>
            </div>
            <br>
            <div id="dateRange" style="display:none">
                <div class="col-md-3">
                    <div class="row">
                        <div class="form__group col-md-6 fg_margin input-group date" data-target-input="nearest">
                            <input type="text" id="searchStartDate" name = "searchStartDate" class="datetimepicker-input form__field" placeholder="Time In" data-target="#searchStartDate" data-toggle="datetimepicker">
                            <label for="searchStartDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>Time In</label>
                        </div>
                        <div class="form__group col-md-5 fg_margin"></div>
                    </div>
                </div>
                <br>
                <div class="col-md-3">
                    <div class="row">
                        <div class="form__group col-md-6 fg_margin input-group date" data-target-input="nearest">
                            <input type="text" id="searchEndDate" name = "searchEndDate" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#searchEndDate" data-toggle="datetimepicker">
                            <label for="searchEndDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>Time Out</label>
                        </div>
                        <div class="form__group col-md-5 fg_margin"></div>
                    </div>
                </div>
            </div>
            <div id="status" style="display:none">
                <div class="col-md-3">
                    <div class="row">
                        <div class="form__group col-md-6 fg_margin">
                            <select id="cmbStatus" name="cmbStatus" class="form__field">
                                <option value="All">All</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <label for="cmbStatus" class="span-header form__label"><i class="icon-right fa fa-bar-chart" aria-hidden="true"></i>Status</label>
                        </div>
                        <div class="form__group col-md-5 fg_margin"></div>
                    </div>
                </div>
            </div>
        <div>





        <!-- <div class="form-group">
        <div class="col-md-3">
                {{-- time in --}}
                <div class="form__group col-md-6 input-group date" data-target-input="nearest">
                    <input type="text" id="timeIn" name = "timeIn" class="datetimepicker-input form__field" placeholder="Time In" data-target="#timeIn" data-toggle="datetimepicker">
                    <label for="timeIn" class="span-header form__label"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Time In</label>
                </div>
                
            </div>
        </div> -->
        <div class="form__group">
            <input id="btnAdd" name="btnAdd" class="btn btn-sm button blue pull-right" type="button" value="Apply For Advance Overtime?" style="width:220px;"/>
        </div>
        <br><br>

        <div id="divOvertimeRecord"class="table-responsive">
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
                            <th colspan="2" scope="colgroup" style="border-top:1px solid #000;">Approval History</th>
                            <tr>
                                
                                <th scope="col">Level 1</th>
                                <th scope="col" >Level 2</th>
                                <th style="border-top:0px;"></th>
                            </tr>
                        </tr>          
                    </thead>
                    <tbody>
                        <tr>
                            <td>2019-04-12</td>
                            <td>2019-04-12 19:30:00</td>
                            <td>2019-04-12 22:30:00</td>
                            <td>Post Shift</td>    
                            <td>Sakit Ulo</td> 
                            <td>3.0</td>    
                            <td>PENDING</td>  
                            <td>PENDING</td> 
                            <td><input type="button" class="btn btn-sm button red btnCancel" value="Cancel Alteration"></td> 
                        
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#cmbSearchBy").change(function(){
        var searchBy = $('#cmbSearchBy').val();
        
        if(searchBy == "All"){
            $('#dateRange').css('display','none');
            $('#status').css('display','none');
            
        }
        else if(searchBy == "Date Range"){
            $('#status').css('display','none');
            $('#dateRange').css('display','block'); 
        }
        else if(searchBy == "Status"){
            $('#dateRange').css('display','none');
            $('#status').css('display','block');          
        }
    });
});


$(function (){
    $('#searchStartDate').datetimepicker({
        format: 'L'
    });
});

$(function (){
    $('#searchEndDate').datetimepicker({
        format: 'L'
    });
});
</script>
@endsection