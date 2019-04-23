@extends('layouts/app')

@section('content')

<style>
    #time_record_container{
        margin-top:10px;
        padding:10px;
        border-left: 3px solid #3C8DBC;
        background-color: white;
        box-shadow: 0px 0px 5px rgba(0,0,0,0.4);
        /* border: 1px solid black; */
    }
    /*#table_time_records td, #table_time_records th {
        border: 1px solid #ddd;

    }
    #table_time_records th {
        border-top:7px solid #ddd;
    }    */
    #table_time_records{
        text-align: center;
    }

   

    .reason{
        border: none;
        background-color: transparent;
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


    <!-- This is your Timerecord Body -->
    <div id="moduleContainer">
            <div class="row">
                <div class="col">
                    <div id="punch_container" class="text-center">
                        <a href="/timerecords/view_punch_alteration">View Punch Alteration Records</a> |            
                        <a href="/timerecords/view_punch_history">Punch History</a>   
                    </div>
                </div>   
            </div>
            <br>
            <form>
                <label><b>Generate Report</b> </label>
                <table>
                    <tr>
                        <td>
                            <div class="form__group input-group date" data-target-input="nearest">
                                <input id="startDate" name="startDate" class="datetimepicker-input form__field" placeholder="Start Date" data-target="#startDate" data-toggle="datetimepicker">
                                <label for="startDate" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Start Date</label>
                            </div>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <div class="form__group input-group date" data-target-input="nearest">
                                <input id="endDate" name="endDate" class="datetimepicker-input form__field" placeholder="End Date" data-target="#endDate" data-toggle="datetimepicker">
                                <label for="endDate" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;End Date</label>
                            </div>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <input type="button" id="btnGenerate" name="btnGenerate" class="btn btn-sm button blue" value="Generate">
                        </td>
                    </tr>
                </table>
            </form>
            <br>
            <div class="form__group col-md-3">
                <textarea id="txtReason_apply" name = "txtReason_apply" class="form__field" placeholder="Reason for Alteration *"></textarea>
                <label for="txtReason_apply" class="span-header form__label"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Reason for Alteration *</label>
            </div>
            <br>
            <input type="button" id="btnApplyAlter" class="pull-right btn btn-sm button blue btnApplyAlter" value="Apply Alteration">

            <br><br>
            {{-- LOADER --}}
            <div class="p_load" id="loader"></div>
            {{-- FORM --}}
            <form id='alteration_data'>
                {{-- <input type="hidden" id="hidden_ID" name="hidden_ID" value={{auth()->user()->company_id }}> --}}
                <div class="table-responsive" id="table_DTR">
                    <table id="table_time_records" name="tableTimeRecords" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>New Time In</th>
                                <th>New Time Out</th>
                                <th>Total Hours</th>
                                <th>Undertime</th>
                                <th>Late</th>
                                <th>Holidays</th>
                                <th>Remarks</th>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>               
        </div>
        <!-- This is your Timerecord Body -->


</div>

<script>
    //This will get the current date
        var curDate = new Date();
        var dd = String(curDate.getDate()).padStart(2, '0');
        var mm = String(curDate.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = curDate.getFullYear();
        curDate = yyyy + '-' + mm + '-' + dd;
    //This will get the current date

    var counter_alter_validation = 0;
    var checker_validation = "false";
    var timein_validation = "false";
    var remarks_validation = "false";
    var error = "";

    $('#table_time_records').dataTable({
        "serverSide": false, 
        "retrieve": true, 
        "ordering": false
    });

    //function refresh 
    refresh_Table();
    function refresh_Table(){
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('printdate') }}",
            method: "GET",
            data:{}, 
            success:function(data)
            {
                
                $('#table_DTR').html(data);
                $('#table_time_records').dataTable({
                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                });
                //for(i = 1; i <=100; i++){
                //    var dateApplied = $('#disabledDate' + i).val();
                //    if (dateApplied == curDate){
                //        $('#timein' + i).attr('disabled','disabled');
                //        $('#timeout' + i).attr('disabled','disabled');
                //    }
                //}
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }


    //function filter dates
    function filterDate()
    {
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        if(startDate == "" || endDate == "")
        {
            alert("No Date selected");
        }
        else
        {
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('filterdate') }}",
                method: "GET",
                data:{start_date: startDate, end_date: endDate}, 
                success:function(data)
                {
                    $('#table_DTR').html(data);
                    $('#table_time_records').dataTable({
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

    //button generate filter dates
    $(document).on('click', '#btnGenerate', function (){    
        
        filterDate();   
    });     

    //apply alteration if no data
    var altered_date = "";
    $(document).on("click", ".btn_punchAlter", function(){
        
        $('#txtReason').val("");
        $('#newlog_datein').html("");
        $('#newlog_dateout').html("");
        $('#reason').html("");
        $('#startDate_newlog').css("display", "block");
        $('#startDate_newlog_ico').css("display", "block");
        $('#endDate_newlog').css("display", "block");
        $('#endDate_newlog_ico').css("display", "block");
        $('#txtReason').css("display", "block");
        $('#btn_ApplyAlter').css("display", "block");
        $("#punchAlterationModal").modal('show');
    
        var cur_log_datetime = $(this).data("add");
        var cur_log_viewdate = cur_log_datetime.split("]]");

        document.getElementById('cur_time_in').innerHTML = cur_log_viewdate[0];
        document.getElementById('cur_time_out').innerHTML = cur_log_viewdate[1];
        
        document.getElementById('date_of_alteration').innerHTML = cur_log_viewdate[3];

        if(cur_log_viewdate[0] == "-" || cur_log_viewdate[1] == "-")
        {
            $('#startDate_newlog').val("");
            $('#endDate_newlog').val("");
        }
        else
        {
            $('#startDate_newlog').val(cur_log_viewdate[0]);
            $('#endDate_newlog').val(cur_log_viewdate[1]);
        }
        altered_date = cur_log_viewdate[3];   
    });

    //button on apply alteration on modal
    $(document).on("click", "#btn_ApplyAlter", function(){
        var c = confirm("Apply this alteration?");

        if(c == true)
        {
            var hidden_Id = $('#hidden_ID').val();
            var cur_time_in = $('#cur_time_in').html();
            var cur_time_out = $('#cur_time_out').html();
            var new_time_in = $('#startDate_newlog').val();
            var new_time_out = $('#endDate_newlog').val();
            var txtReason_apply = $('#txtReason_apply').val();
            


            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('applyalteration') }}",
                method: "POST",
                data:{hidden_Id: hidden_Id, altered_date: altered_date, cur_time_in: cur_time_in, cur_time_out: cur_time_out, new_time_in: new_time_in, new_time_out: new_time_out, txtReason_apply:txtReason_apply}, 
                dataType: "json",
                success:function(data)
                {
                    if(data.error.length > 0)
                    {               
                        var x="";     
                        for(var count = 0;count < data.error.length; count++)
                        {
                            x += data.error[count] + "\n";
                        }
                        alert(x);
                    }
                    else
                    {                             
                        alert(data.success);
                        $("#punchAlterationModal").modal('hide');
                        refresh_Table();
                        
                    }                   
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
        else
        {
            
        }
        
    });

    //view alter when there is record
    $(document).on("click", '.btn_viewAlter', function(){

        $("#punchAlterationModal").modal('show');
        $('#modalTitle').html("<strong>View Punch Alteration</strong>");
        var data = $(this).data("add");

        var view_data = data.split("]]");

        document.getElementById('date_of_alteration').innerHTML = view_data[0];
        document.getElementById('cur_time_in').innerHTML = view_data[1];
        document.getElementById('cur_time_out').innerHTML = view_data[2];
        $('#newlog_datein').html(view_data[3]);
        $('#newlog_dateout').html(view_data[4]);
        $('#reason').html(view_data[5]);
        $('#startDate_newlog').css("display", "none");
        $('#startDate_newlog_ico').css("display", "none");
        $('#endDate_newlog').css("display", "none");
        $('#endDate_newlog_ico').css("display", "none");
        $('#txtReason').css("display", "none");
        $('#btn_ApplyAlter').css("display", "none");
    });

    var datas = "";
    var a = "";
    $(document).on("click", ".chkbox", function(){

        a = $(this).data("add");
        
        var b = $('#infos'+a).val();

        datas = b.split("]]");

        //alert(datas[0]);
       
    });
    var az = "";
    // function validation
    function validation_altered_data()
    {   
        var a = $('select[name=table_time_records_length]').val();
        $('select[name=table_time_records_length]').append("<option id='optioncount' value='365'></option>");
        $('select[name=table_time_records_length]').val(365).trigger('change');

        counter_alter_validation = 0;
        for(i = 1; i <=100; i++)
        {
            var sam = $('#timein' + i).val();
            var date = new Date(sam);
            month = date.getMonth()+1;

            if(month < 10)
            {
                var month_format = "0" + (date.getMonth()+1);
            }
            else
            {
                var month_format = (date.getMonth()+1);
            }

            var this_date = date.getDate();
            if(this_date >= 10)
            {
                var day_format = date.getDate();
            }
            else
            {
                var day_format = "0"+ date.getDate();
            }

            var res = date.getFullYear() + "-" + month_format + "-" + day_format;
            
            if($('#checkalt' + i))
            {
                if($('#checkalt' + i).prop("checked") == true)
                {
                    var date_to_alter = $('#infos' + i).val().split("]]");
                    
                    counter_alter_validation++;
                    if($('#txtReason_apply').val() == ""){
                        az = "1";
                        checker_validation = "false";
                        error="Reason for Alteration is Required!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;
                    }
                    //If time in or time out IS EMPTY
                    else if($('#timeout' + i).val() == "" || $('#timein' + i).val() == "")
                    {
                        az = "2";
                        checker_validation = "false";
                        error="Time In field and Time Out field is required!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;
                    }
                    else if($('#timeout' + i).val() <= $('#timein' + i).val())
                    {
                        az = "3";
                        checker_validation = "false";
                        error="Time Out field must be greater than Time In field!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;
                    }
                    else if(res != date_to_alter[0])
                    {
                        az = "4";
                        //alert(res);
                        checker_validation = "false";
                        error="Invalid Details, Check Time in and Time out!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;
                    }
                    // else if($('#txtremarks' + i).val() == "")
                    // {
                    //     az = "5";
                    //     checker_validation = "false";
                    //     error="Complete remarks!";
                    //     $("select[name=table_time_records_length] option[value='365']").remove(); 
                    //     $('select[name=table_time_records_length]').val(a).trigger('change');
                    //     break;
                    // }

                    //Avoid saving the current date, If the user removes disabled attribute in the inspect elements
                    else if(date_to_alter[0] == curDate)
                    {
                        az = "5";
                        checker_validation = "false";
                        error="You can't alter the current date!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;validateTimein
                    }
                    else if($('#validateStatus' + i).val() == "Punch Altered")
                    {
                        az = "6";
                        checker_validation = "false";
                        error = "You can't alter this row!";
                        $("select[name=table_time_records_length] option[value='365']").remove(); 
                        $('select[name=table_time_records_length]').val(a).trigger('change');
                        break;
                    }
                    else
                    {
                        checker_validation = "true";
                    }
                }
            }
        }
    }

    //button apply alteration
    $(document).on("click", ".btnApplyAlter", function(){

        var a = $('select[name=table_time_records_length]').val();
        //alert(az);
        validation_altered_data();    
        if(counter_alter_validation == 0)
        {
            $("select[name=table_time_records_length] option[value='365']").remove(); 
            $('select[name=table_time_records_length]').val(a).trigger('change');
            alert("Check the row you want to apply alter");   
        }
        else if(checker_validation == "false")
        { 
            $("select[name=table_time_records_length] option[value='365']").remove(); 
            $('select[name=table_time_records_length]').val(a).trigger('change');
            alert(error);
        }
        else
        {
            var c = confirm("Apply this alteration?");
            if(c == true)
            {
                $('.reason').prop("disabled", false);

                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('applyalteration') }}",
                    method: "POST",
                    data: $('#alteration_data').serialize(), 
                    beforeSend: function()
                    {
                        document.getElementById('loader').style.display = "block";
                    },
                    success:function(data)
                    {
                        if(data == "w")
                        {
                            alert("You don't have schedule for this day!");
                            $("select[name=table_time_records_length] option[value='365']").remove(); 
                            $('select[name=table_time_records_length]').val(a).trigger('change');   
                            $('.reason').prop("disabled", true);   
                        }
                        else
                        {
                            $("select[name=table_time_records_length] option[value='365']").remove(); 
                            $('select[name=table_time_records_length]').val(a).trigger('change');          
                            alert("Alteration Applied!");
                            // refresh_Table(); 
                        } 
                    },
                    complete:function(){
                        refresh_Table();
                        document.getElementById('loader').style.display = "none";
                    }
                }); 
            }
            else{
                $("select[name=table_time_records_length] option[value='365']").remove(); 
                $('select[name=table_time_records_length]').val(a).trigger('change');
            }
        } 
    });
</script>

<script>
    //start format
    $(function (){
        $('#startDate').datetimepicker({
            format: 'L'
        });
    });
    //end format
    $(function (){
        $('#endDate').datetimepicker({
            format: 'L'
        });
    });
</script>
@endsection