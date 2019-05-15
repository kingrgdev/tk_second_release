@extends('layouts/app')

@section('content')
<div id="con_page" class="container-fluid">  

        <h4>Punch Alteration Records</h4>    

    @include('modules/usersmodule/userprofile/user_profile')
    
    <div id="moduleContainer">
            <div class="row">
                    <div class="col">
                        <div id="punch_container" class="text-center">
                            <a href="/timerecords">Back to Time Records</a> |            
                            <a href="/timerecords/view_punch_history">Punch History</a>   
                        </div>
                    </div>   
                </div>
                <br>
        <form>
            <table>       
                <tr>
                    <td>

                        <div class="form__group fg_margin" style="width:200px;">
                            <select id="search_By" name="search_By" class="form__field" placeholder="Search By:">
                                <option value="ALL">ALL</option>
                                <option value="Alteration_Date">Alteration Date</option>
                                <option value="Date_Applied">Date Applied</option>
                                <option value="Status">Status</option>
                            </select>
                            <label for="search_By" class="span-header form__label"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search By</label>
                        </div>
                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <td>
                    <input type="button" id="btn_Generate" name="btn_Generate" class="btn btn-sm button blue" value="Generate">
                    </td>
                </tr>
            </table>
        </form>

        <br>
        {{-- Alteration Date --}}
        <div class="row">
            <div id="alteration_date" style="display:none" class="b-2m">
                <div class="container">
                    <table>
                        <tr>
                            <td>
                                <div class="form__group input-group date" data-target-input="nearest">
                                    <input id="startDate_AD" name="startDate_AD" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#startDate_AD" data-toggle="datetimepicker">
                                    <label for="startDate_AD" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Start Date</label>
                                </div>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td>
                                <div class="form__group input-group date" data-target-input="nearest">
                                    <input id="endDate_AD" name="endDate_AD" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#endDate_AD" data-toggle="datetimepicker">
                                    <label for="endDate_AD" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;End Date</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>    
        </div>
        {{-- END --}}
        

        {{-- Date Applied --}}
        <div class="row">
            <div id="date_applied" style="display:none" class="mb-2">
                <div class="container">
                        <table>
                            <tr>
                                <td>
                                    <div class="form__group input-group date" data-target-input="nearest">
                                        <input id="startDate_DA" name="startDate_DA" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#startDate_DA" data-toggle="datetimepicker">
                                        <label for="startDate_DA" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Start Date</label>
                                    </div>
                                </td>
                                <td>&nbsp;&nbsp;</td>
                                <td>
                                    <div class="form__group input-group date" data-target-input="nearest">
                                        <input id="endDate_DA" name="endDate_DA" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#endDate_DA" data-toggle="datetimepicker">
                                        <label for="endDate_DA" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;End Date</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
            
        </div>
        {{-- END --}}

        {{-- Status --}}
        <div class="row">
            <div id="status" style="display:none" class="mb-2">
                <div class="container">
                    <div class="form__group fg_margin" style="width:200px;">
                        <select id="status_By" name="status_By" class="form__field" placeholder="Status By:">
                                <option value="All">All</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Retracted">Retracted</option>
                        </select>
                        <label for="status_By" class="span-header form__label"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Status By</label>
                    </div>
                </div>
            </div> 
        </div>
        {{--- END ---}}

        <br><br>
        <div id="punch_alter_table">
            @include("modules/usersmodule/timerecords/table/punch_alter_table")
        </div> 

    </div>
</div>

<script>
    //dropdown 
        var to_do = "";
        var action = "";
        $("#search_By").change(function(){
            to_do = $('#search_By').val();
            if(to_do == "Alteration_Date")
            {
                $('#date_applied').hide();
                $('#status').hide();
                $('#alteration_date').show();
                action = "alteration_date";
                //alert(action);
            }
            else if(to_do == "Date_Applied")
            {
                $('#alteration_date').hide();
                $('#status').hide();
                $('#date_applied').show();
                action = "date_applied";
            }
            else if(to_do == "Status")
            {
                $('#status').css('display','block');
                $('#date_applied').css('display','none');
                $('#alteration_date').css('display','none');
                action = "status";
            }
            else if(to_do == "All")
            {
                $('#date_applied').css('display','none');
                $('#alteration_date').css('display','none');
                $('#status').css('display','none');
                action = "all";
            }
        });
    //dropdown 

    //status by filter
        var status_to_do = "";
        var status_action = "";
        $("#status_By").change(function(){
            status_to_do = $('#status_By').val();
            if(status_to_do == "All")
            {
                status_action = "All";
            }
            else if(status_to_do == "Pending")
            {
                status_action = "Pending";
            }
            else if(status_to_do == "Cancelled")
            {
                status_action = "Cancelled";
            }
            else if(status_to_do == "Approved")
            {
                status_action = "Approved";
            }
            else if(status_to_do == "Rejected")
            {
                status_action = "Rejected";
            }
            else if(status_to_do == "Retracted")
            {
                status_action = "Retracted";
            }
        });
    //status by filter

    //function calltable
        callTable('{{ route("refreshtable_alteration") }}');
        function callTable(url)
        {
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: url,
                method: "GET",
                data:{}, 
                success:function(data)
                {
                    $('#punch_alter_table').html(data);
                    $('#table_punch_alteration_record').dataTable({
                        "serverSide": false, 
                        "retrieve": true, 
                        "ordering": false
                    });             
                }
            });
        }
    //function calltable

    //generate button
        $(document).on("click", "#btn_Generate", function(){
            
            //all
            if(action == "all")
            {
                var url = '{{ route("searchall") }}';
                callTable(url); 
            }

            //alteration date
            else if(action == "alteration_date")
            {
                //alert(action);
                var start_date = $('#startDate_AD').val();
                var end_date = $('#endDate_AD').val();
                
                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('searchalteration') }}",
                    method: "GET",
                    data:{start_date_alteration: start_date, end_date_alteration: end_date}, 
                    success:function(data)
                    { 
                        $('#punch_alter_table').html(data);
                        $('#table_punch_alteration_record').dataTable({
                            "serverSide": false, 
                            "retrieve": true, 
                            "ordering": false
                        });               
                    }
                });
            }
            //date applied
            else if(action == "date_applied")
            {
                var start_date = $('#startDate_DA').val();
                var end_date = $('#endDate_DA').val();
                
                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('searchapplied') }}",
                    method: "GET",
                    data:{start_date_applied: start_date, end_date_applied: end_date}, 
                    success:function(data)
                    {
                        $('#punch_alter_table').html(data);
                        $('#table_punch_alteration_record').dataTable({
                            "serverSide": false, 
                            "retrieve": true, 
                            "ordering": false
                        });               
                    }
                });
            }
            //status
            else if(action == "status")
            {
                //all
                if(status_action == "All")
                {
                    var url = '{{ route("statusall") }}';
                    callTable(url); 
                    
                }
                //pending
                else if(status_action == "Pending")
                {
                    var url = '{{ route("statuspending") }}';
                    callTable(url); 
                }
                //approved
                else if(status_action == "Approved")
                {
                    var url = '{{ route("statusapprove") }}';
                    callTable(url); 
                }
                //rejected
                else if(status_action == "Rejected")
                {
                    var url = '{{ route("statusrejected") }}';
                    callTable(url); 
                }
                //cancelled
                else if(status_action == "Cancelled")
                {
                    var url = '{{ route("statuscancel") }}';
                    callTable(url); 
                }
                //retracted
                else if(status_action == "Retracted")
                {
                    var url = '{{ route("statusretracted") }}';
                    callTable(url); 
                }
            } 
        });
    //generate button

</script>

{{-- datepicker on home --}}
    <script type="text/javascript">
        
        $('#table_punch_alteration_record').dataTable({
            "serverSide": false, 
            "retrieve": true, 
            "ordering": false
        });
    
        $(function () {
            $('#startDate_AD').datetimepicker({
                format: 'L'
            });
        });

            $(function () {
            $('#endDate_AD').datetimepicker({
                format: 'L'
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            $('#startDate_DA').datetimepicker({
                format: 'L'
            });
        });

            $(function () {
            $('#endDate_DA').datetimepicker({
                format: 'L'
            });
        });
    </script>
{{-- datepicker on home --}}

<script>
    //refresh table
        function refresh_Table(){
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('refreshtable_alteration') }}",
                method: "GET",
                data:{}, 
                success:function(data)
                {
                    $('#punch_alter_table').html(data);
                    $('#table_punch_alteration_record').dataTable({
                        "serverSide": false, 
                        "retrieve": true, 
                        "bStateSave":true,
                        "ordering": false
                    });               
                }
            });
        }
    //refresh table

    //cancel alteration
        $(document).on("click", ".btn_Cancel", function(){
            
            var id_to_cancel = $(this).data("add");
            var c = confirm("Do you want to cancel this alteration?");

            if(c == true)
            {
                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('cancelalteration') }}",
                    method: "POST",
                    data:{id_to_cancel: id_to_cancel}, 
                    dataType: "json",
                    success:function(data)
                    {
                        alert(data);
                        refresh_Table();
                    }
                });
            }
        });
    //cancel alteration
</script>

<br><br>
@endsection