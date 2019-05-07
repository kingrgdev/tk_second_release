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
    refresh_Table();
    function refresh_Table(){
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
@endsection