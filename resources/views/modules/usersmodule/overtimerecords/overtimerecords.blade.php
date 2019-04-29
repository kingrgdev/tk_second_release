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
                        <input id="btnFilter" name="btnFilter" class="btn btn-sm button blue btnFilter" type="button" value="Generate" style="width:100px;"/>
                    </div>
                </div>
            </div>
            <br>
            <div id="dateRange" style="display:none">
                <div class="col-md-3">
                    <div class="row">
                        <div class="form__group col-md-6 fg_margin input-group date" data-target-input="nearest">
                            <input type="text" id="searchStartDate" name = "searchStartDate" class="searchInput datetimepicker-input form__field" placeholder="Time In" data-target="#searchStartDate" data-toggle="datetimepicker">
                            <label for="searchStartDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>Time In</label>
                        </div>
                        <div class="form__group col-md-5 fg_margin"></div>
                    </div>
                </div>
                <br>
                <div class="col-md-3">
                    <div class="row">
                        <div class="form__group col-md-6 fg_margin input-group date" data-target-input="nearest">
                            <input type="text" id="searchEndDate" name = "searchEndDate" class="searchInput datetimepicker-input form__field" placeholder="Time Out" data-target="#searchEndDate" data-toggle="datetimepicker">
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

            <label class="pull-right"><b>Apply for Advance Overtime? </b><span id="overtimeIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
            <br><br>
            {{-- apply overtime --}}
            <div id="hideOvertimeField" style="display:none">

            
                <div class="form-group row">
                    <div class="col-md-6">

                        {{-- overtime date --}}
                        <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                            <input id="schedDate" name = "schedDate" type="text" class="datetimepicker-input form__field" placeholder="Schedule Date" data-target="#schedDate" data-toggle="datetimepicker">
                            <label for="schedDate" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Schedule Date</label>
                        </div>
                        <br>

                        {{-- time in --}}
                         <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                            <input id="timeIn" name = "timeIn" type="text" class="datetimepicker-input form__field" placeholder="Time In" data-target="#timeIn" data-toggle="datetimepicker">
                            <label for="timeIn" class="span-header form__label"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Time In</label>
                        </div>
                        <br>

                        

                        {{-- time out --}}
                        <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                            <input id="timeOut" name = "timeOut" type="text" class="datetimepicker-input form__field" placeholder="Time Out" data-target="#timeOut" data-toggle="datetimepicker">
                            <label for="timeOut" class="span-header form__label"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Time Out</label>
                        </div>
                        <br>
                        
                        <div class="form__group col-md-8 fg_margin">
                                <select id="cmbShift" name = "cmbShift" class="form__field" placeholder="Shift Type">
                                        <option value="">- Select Shift -</option>
                                        <option value="Pre-Shift">Pre-Shift</option>
                                        <option value="Post-Shift">Post-Shift</option>
                                </select>
                            <label for="cmbShift" class="span-header form__label"><i class="fa fa-server" aria-hidden="true"></i>&nbsp;Shift Type</label>
                        </div>
                    </div>


                    <div class="col-md-6">
                    <br>
                        {{-- reason --}}
                        <div class="form__group col-md-8 fg_margin">
                            <textarea type="text" id="txtReason" name = "txtReason" class="form__field" placeholder="Reason"></textarea>
                            <label for="txtReason" class="span-header form__label"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Reason</label>
                        </div>

                        <div class="form__group col-md-8 fg_margin">
                            <div class="container form-group row">
                            <input id="btnApplyAlter" name="btnApplyAlter" class="btnApplyAlter btn btn-sm button blue pull-right" type="button" value="Apply Overtime" style="width:220px;"/>
                            </div>
                        </div>
                    </div>
                </div>

       
            </div>
            <br><br>
        <div class="p_load" id="loader"></div>
        <div id="divOvertimeRecord"class="table-responsive">
                <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="tableOvertimeRecord table table-hover" style="width:100%">
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>    
                            <td></td> 
                            <td></td>    
                            <td></td>  
                            <td></td> 
                            <td></td> <!--- <input type="button" class="btn btn-sm button red btnCancel" value="Cancel Alteration"> --->
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

$(function (){
    $('#schedDate').datetimepicker({
        format: 'L'
    });
});

$(function (){
    $('#timeIn').datetimepicker({
        format: 'LT'
    });
});
</script>



<script>
    $('#tableOvertimeRecord').DataTable({
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
            url: "{{ route('printovertime') }}",
            method: "GET",
            data:{}, 
            success:function(data)
            {
                
                $('#divOvertimeRecord').html(data);
                
                $('#tableOvertimeRecord').DataTable({
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



//overtime field
$(document).on("click", "#overtimeIcon", function(){
        if($("#hideOvertimeField").is(":visible")){

            $("#overtimeIcon").removeClass("fa-caret-square-o-down");
            $("#overtimeIcon").addClass("fa fa-caret-square-o-right");
            $("#hideOvertimeField").stop().slideUp(150);
        }
        else{

            $("#overtimeIcon").removeClass("fa-caret-square-o-right");
            $("#overtimeIcon").addClass("fa-caret-square-o-down");
            $("#hideOvertimeField").stop().slideDown(150);
            
        }
    });
//overtime field


//cancel overtime
    $(document).on("click", ".btnCancel", function(){
            
        var id_to_cancel = $(this).data("add");
        var c = confirm("Do you want to cancel this overtime?");

        if(c == true)
        {
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('cancelovertime') }}",
                method: "POST",
                data:{id_to_cancel: id_to_cancel}, 
                dataType: "json",
                success:function(data)
                {
                    alert(data);
                    refresh_Table();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
//cancel alteration
</script>


<script>
//button apply alteration//
$(document).on("click", ".btnApplyAlter", function(){

var datetimein = $("#schedDate").val() + " " + $("#timeIn").val();
var datetimeout = $("#timeOut").val();

if($("#schedDate").val() == ""){
    
    alert("Schedule Date Field Required!");
}
else if($("#timeIn").val() == "")
{
    alert("Time In Required!");
}
else if($("#timeOut").val() == "")
{
    alert("Time Out Required!");
}
else if($("#cmbShift").val() == "")
{
    alert("Shift Type Field Required!");
}
else if($("#txtReason").val() == "")
{
    alert("Reason Field Required!");
}
// else if(datetimeout <= datetimein)
// {
//     alert(datetimeout);
// }
else
{
    var c = confirm("Apply this overtime?");
    if(c == true)
    {
        var schedDate = $("#schedDate").val();
        var timeIn = $("#timeIn").val();
        var timeOut = $("#timeOut").val();
        var cmbShift = $("#cmbShift").val();
        var txtReason = $("#txtReason").val();

        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('saveovertime') }}",
            method: "POST",
            data: {schedDate: schedDate, timeIn:timeIn, timeOut:timeOut, cmbShift:cmbShift, txtReason: txtReason},
            success:function(data)
            {
                alert(data);
                refresh_Table();
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        }); 
    }
}
});
//button apply alteration//


//button generate filter dates
$(document).on('click', '#btnFilter', function (){        
    filterDate();   
});
//function filter dates
function filterDate()
{
    var startDate = $('#searchStartDate').val();
    var endDate = $('#searchEndDate').val();

    if(startDate == "" || endDate == "")
    {
        alert("No Date selected");
    }
    else
    {
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('filterdates') }}",
            method: "GET",
            data:{start_date: startDate, end_date: endDate}, 
            success:function(data)
            {
                $('#divOvertimeRecord').html(data);
                $('#tableOvertimeRecord').DataTable({
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
}
</script>

@endsection