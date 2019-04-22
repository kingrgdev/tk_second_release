@extends('layouts/app')

@section('content')
<div id="con_page" class="container-fluid">

    <h1>Punch History</h1>

    @include('modules.usersmodule.userprofile.user_profile')

    <div id="moduleContainer">
            <div class="row">
                    <div class="col">
                        <div id="punch_container" class="text-center">
                            <a href="/timerecords">Back to Time Records</a>    |   
                            <a href="/timerecords/view_punch_alteration">View Punch Alteration Records</a>         
                        </div>
                    </div>   
                </div>
                <br>
        <form>
            <label><b>Generate History</b></label>
            <table>
                <tr>
                    <td>
                        
                            <div class="form__group input-group date" data-target-input="nearest">
                                <input id="startDate" name="startDate" class="datetimepicker-input form__field" placeholder="From" data-target="#startDate" data-toggle="datetimepicker">
                                <label for="startDate" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;From</label>
                            </div>
                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <td>
                        <div class="form__group input-group date" data-target-input="nearest">
                            <input id="endDate" name="endDate" class="datetimepicker-input form__field" placeholder="Start" data-target="#endDate" data-toggle="datetimepicker">
                            <label for="endDate" class="span-header form__label"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Start</label>
                        </div>

                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <td>
                        <input type="button" id="btnGenerate" name="btnGenerate" class="btn btn-sm button blue" value="Generate">
                    </td>
                </tr>
            </table>
            
        </form>
        <br><br>
        <div id="div_punch_history" class="table-responsive">
            <table id="table_punch_history" name="table_punch_history" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th style='text-align:center;'>Total</th>
                        <th style='text-align:center;'>Overtime</th>
                        <th style='text-align:center;'>Considered Hours</th>
                        <th>Holiday</th>
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- datepicker on home --}}
<script type="text/javascript">

    $(document).ready(function() {
        $('#table_punch_history').DataTable();
    });

    $(function () {
        $('#startDate').datetimepicker({
            format: 'L'
        });
    });

     $(function () {
        $('#endDate').datetimepicker({
            format: 'L'
        });
    });
</script>

<script>
// get punch history
    refreshTable();
    function refreshTable(){
        
    // refresh punch history
    $.ajax({
    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    url: "{{ route('historylist') }}",
    method: "GET",
    data:{},
    success:function(data){
        $('#div_punch_history').html(data);
        $('#table_punch_history').dataTable({
        
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
</script>
<script>
    //button generate filter dates
    $(document).on('click', '#btnGenerate', function (){    
        filterDate();   
    });
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
                url: "{{ route('historyfilter') }}",
                method: "GET",
                data:{start_date: startDate, end_date: endDate}, 
                success:function(data)
                {
                    $('#div_punch_history').html(data);
                    $('#table_punch_history').dataTable({
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
<br><br>
@endsection