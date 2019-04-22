@extends('layouts/app')

@section('content')
<style>
    #tableEmployeeList, #tableOvertimeRecord, #tableResult{
        text-align:center;
    }
    #tableEmployeeList th, #tableOvertimeRecord th{
        border-top:1px solid #ddd;
    }
</style>

<div id="pageContainer" class="container-fluid">
    <h1><i class="icon-right fa fa-plus-circle" aria-hidden="true"></i>Add Overtime</h1>
    
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

        {{-- overtime tabs --}}
        <ul class="nav nav-tabs" id="overtimeTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="btnTabOvertime" data-toggle="tab" href="#tabOvertime" role="tab" aria-controls="btnTabOvertime" aria-selected="true">Overtime</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="btnTabOvertimeRecord" data-toggle="tab" href="#tabOvertimeRecord" role="tab" aria-controls="btnTabOvertimeRecord" aria-selected="false">Overtime Records</a>
            </li>
        </ul>

        <form id="formOvertime">
            <div class="tab-content" id="myTabContent">
                {{-- tab overtime --}}
                <div class="tab-pane fade show active" id="tabOvertime" role="tabpanel" aria-labelledby="overtime-tab">
                    <div class=form-group>
                        <label class="pull-right"><b>Overtime Field </b><span id="overtimeIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
                    </div>
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
                                {{-- total hours --}}
                                <!-- {{-- <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for='txtReason'><b>Total Hours&nbsp;</b></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="txtTotalHours" name="txtTotalHours" rows="3" placeholder="0.0"></input>
                                    </div>
                                </div> --}} -->

                                <!-- {{-- shift type --}} -->
                                <div class="form__group col-md-8 fg_margin">
                                        <select id="cmbShift" name = "cmbShift" class="form__field" placeholder="Shift Type">
                                                <option value="">- Select Shift -</option>
                                                <option value="Pre-Shift">Pre-Shift</option>
                                                <option value="Post-Shift">Post-Shift</option>
                                        </select>
                                    <label for="cmbShiftType" class="span-header form__label"><i class="fa fa-server" aria-hidden="true"></i>&nbsp;Shift Type</label>
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
                                    <input id="btnAdd" type="button" class="btn btn-sm button blue" value="Add Overtime" style="width:150px;"/>
                                    
                                    {{-- check all --}}
                                    <input id="selectCount" name="selectCount" type="hidden" value=""/>
                                    <div class="custom-control custom-checkbox ml-3">
                                        <input id="checkAll" name="checkAll" type="checkbox" class="custom-control-input"/>
                                        <label class="custom-control-label" for="checkAll"><b>Check All</b></label>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    {{-- overtime status --}}
                    <div id="overtimeStatus" class="row text-center" style="display:none;">
                        <div class="col-sm-12">                         
                            <input id="btnFail" name="btnFail" type="button" class="btn btn-sm button red" value="0 failed" style=" font-weight: bold">    
                            <input id="btnSuccess" name="btnSuccess" type="button" class="btn btn-sm button green" value="0 success" style=" font-weight: bold">
                        </div>
                    </div>

                    {{-- table overtime list --}}
                    <div id="divEmployeeList" class="table-responsive">
                        <table id="tableEmployeeList" name="tableEmployeeList" class="table table-striped" cellspacing="0" cellspacing="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Team</th>
                                    <th>Employment Status</th>
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

                {{-- overtime record --}}
                <div class="tab-pane fade" id="tabOvertimeRecord" role="tabpanel" aria-labelledby="overtimeRecord-tab">
                    <br>
                    {{-- table record --}}
                    <div class="table-responsive" id="divOvertimeRecord">
                        <table id="tableOvertimeRecord" name="tableOvertimeRecord" class="table table-striped" cellspacing="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Company ID</th>
                                    <th>Employee Name</th>
                                    <th>Date Applied</th>
                                    <th>Applied Time In</th>
                                    <th>Applied Time Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
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
                    <table id="tableResult" name="tableResult" class="table table-striped" cellspacing="0" style="width:100%" >
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- end --}}
<script>

    //get employee info and get overtime record
    refreshTable();
    function refreshTable(){

        // refresh employee list
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getemployeeinfo') }}",
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
            url: "{{ route('getovertimerecord') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divOvertimeRecord').html(data);
                $('#tableOvertimeRecord').dataTable({

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

        //overtime employee list table
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchemployee') }}",
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

        //overtime records
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchovertimerecord') }}",
            method: "GET",
            data:{lastname:lname, firstname:fname, company:companyind, department:deptval, team:team, status:status}, 
            success:function(data){
                $("#divOvertimeRecord").html(data);
                $('#tableOvertimeRecord').dataTable({
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
    $(document).on("click", "#btnFail", function(){
        
        $('#modalResult').modal('show');
        $('#bodyFail').show();
        $('#bodySuccess').hide();
        $('#modalTitle').html('Failed Overtime');
        document.getElementById("tableResult").style.borderTop = "7px solid #DD4B39";
        
    });

    //modal success view
    $(document).on("click", "#btnSuccess", function(){
        
        $('#modalResult').modal('show');
        $('#bodySuccess').show();
        $('#bodyFail').hide();
        $('#modalTitle').html('Success Overtime');
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
    //end clear field

    //addovertime
    $(document).on("click", "#btnAdd", function(){

        var a = $('select[name=tableEmployeeList_length]').val();
        checkSelect();
        //check overtime
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkovertime') }}",
            method: "GET",
            data:$('#formOvertime').serialize(),
            dataType: "json", 
            success:function(data){

                if(data.error.length > 0){

                    $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                    $('select[name=tableEmployeeList_length]').val(a).trigger('change');

                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want apply overtime?") == true){

                        $('#btnSuccess').val("0 success");
                        $('#btnFail').val("0 failed");
                        $("#bodySuccess").html("");
                        $("#bodyFail").html("");

                        var res_success = "";
                        var res_fail = "";
                        
                        //add overtime
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('addovertime') }}",
                            method: "POST",
                            data:$('#formOvertime').serialize(),
                            dataType: "json", 
                            success:function(data){
                                if(data.error.length > 0){
                                    for(var count = 0;count < data.error.length; count++){
                                        
                                        $('#btnFail').val(data.error.length + " failed")
                                        var err = data.error[count].split("]]");
                                        res_fail += "<tr><td>"+ err[0] +"</td>"; 
                                        res_fail += "<td>"+ err[1] +"</td>"; 
                                        res_fail += "<td>"+ err[2] +"</td></tr>"; 
                                        
                                        $("#bodyFail").html(res_fail);
                                        $('#tableResult').dataTable({
                                            "serverSide": false, 
                                            "retrieve": true, 
                                            "ordering": false,
                                        });
                                    }
                                    // alert(data.error[0]);
                                }
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
                                    // alert(data.success[0]);
                                }
                                $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                                $('select[name=tableEmployeeList_length]').val(a).trigger('change');

                                alert("Overtime Process Complete!");
                                refreshTable();
                                $('#overtimeStatus').css("display", "block");
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
    //add end overtime

    //cancel overtime
    $(document).on("click", ".btnCancel", function(){

        var id = $(this).data("add");
        if(confirm("Are you sure you want to cancel overtime to this employee?") == true){

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('cancelovertime') }}",
                method: "POST",
                data: {id:id}, 
                success:function(data)
                {
                    alert("Overtime successfully cancel!");
                    refreshTable();
                }
            }); 
        }
    });
    //end cancel overtime
</script>

<script>

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

    //date format
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

     $(function (){
        $('#timeOut').datetimepicker();
    });
</script>

@endsection