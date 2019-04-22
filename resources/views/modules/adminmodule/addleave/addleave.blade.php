@extends('layouts/app')

@section('content')
<style>
    #tableEmployeeList, #tableLeaveRecord, #tableResult{
        text-align:center;
    }
    #tableEmployeeList th, #tableLeaveRecord th{
        border-top:1px solid #ddd;
    }
</style>

<div id="pageContainer" class="container-fluid">
    <h1><i class="icon-right fa fa-plus-circle" aria-hidden="true"></i>Add Leave</h1>

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

        {{-- leave tabs --}}
        <ul class="nav nav-tabs" id="leaveTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="btnTabLeave" data-toggle="tab" href="#tabLeave" role="tab" aria-controls="btnTabLeave" aria-selected="true">Leave</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="btnTabLeaveRecord" data-toggle="tab" href="#tabLeaveRecord" role="tab" aria-controls="btnTabLeaveRecord" aria-selected="false">Leave Records</a>
            </li>
        </ul>

        <form id="formLeave">
            <div class="tab-content" id="myTabContent">
                {{-- tab leave --}}
                <div class="tab-pane fade show active" id="tabLeave" role="tabpanel" aria-labelledby="leave-tab">
                    <div class=form-group>
                        <label class="pull-right"><b>Leave Field </b><span id="leaveIcon" class="fa fa-caret-square-o-right fa-lg" style="cursor:pointer;"></span></label>
                    </div>
                    <br><br>

                    {{-- apply leave --}}
                    <div id="hideLeaveField" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-6">

                                {{-- kind of leave --}}
                                <div class="form__group col-md-8 fg_margin">
                                    <select id="cmbLeaveType" name = "cmbLeaveType" class="form__field" placeholder="Leave">
                                        <option value="">Select Leave</option>
                                    </select>
                                    <label for="cmbLeaveType" class="span-header form__label"><i class="icon-right fa fa-bars" aria-hidden="true"></i>Leave</label>
                                </div>
                                <br>

                                {{-- leave date --}}
                                <div class="form__group col-md-8 fg_margin input-group date" data-target-input="nearest">
                                    <input id="schedDate" name="schedDate" class="datetimepicker-input form__field" placeholder="Schedule Date" data-target="#schedDate" data-toggle="datetimepicker">
                                    <label for="schedDate" class="span-header form__label"><i class="icon-right fa fa-calendar" aria-hidden="true"></i>Schedule Date</label>
                                </div>
                                <br>

                                {{-- kind of leave --}}
                                <div class="form__group col-md-8 fg_margin">
                                    <select id="cmbLeaveDays" name = "cmbLeaveDays" class="form__field" placeholder="How many days">
                                        <option value="">Select Days</option>
                                    </select>
                                    <label for="cmbLeaveDays" class="span-header form__label"><i class="icon-right fa fa-calendar-plus-o" aria-hidden="true"></i>How many days</label>
                                </div>

                            </div>
                            <div class="col-md-6">
                            <br>
                                {{-- reason --}}
                                <div class="form__group col-md-8 fg_margin">
                                    <textarea id="txtReason" name="txtReason" type="text"  class="form__field" placeholder="Reason"></textarea>
                                    <label for="txtReason" class="span-header form__label"><i class="icon-right fa fa-pencil" aria-hidden="true"></i>Reason</label>
                                </div>

                                <div class="form__group col-md-8 fg_margin">
                                    <div class="container form-group row">
                                        <input id="btnAdd" type="button" class="btn btn-sm button blue" value="Add Leave" style="width:150px;"/></td>
                                        
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

                    {{-- leave status --}}
                    <div id="leaveStatus" class="row text-center" style="display:none;">
                        <div class="col-sm-12">   
                            <input id="btnFail" name="btnFail" type="button" class="btn btn-sm button red" value="0 failed" style=" font-weight: bold">    
                            <input id="btnSuccess" name="btnSuccess" type="button" class="btn btn-sm button green" value="0 success" style=" font-weight: bold">
                        </div>
                    </div>
    
                    {{-- table leave list --}}
                    <div id="divEmployeeList" class="table-responsive">
                        <table id="tableEmployeeList" name="tableEmployeeList" class="table table-hover" cellspacing="0" style="width:100%" >
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
                {{-- leave record --}}
                <div class="tab-pane fade" id="tabLeaveRecord" role="tabpanel" aria-labelledby="leaveRecord-tab">
                <br>
                    {{-- table record --}}
                    <div class="table-responsive" id="divLeaveRecord">
                        <table id="tableLeaveRecord" name="tableLeaveRecord" class="table table-hover" cellspacing="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Company ID</th>
                                    <th>Employee Name</th>
                                    <th>Date Applied</th>
                                    <th>Type of Leave</th>
                                    <th>Duration</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
{{-- end --}}

<script>
//get employee info and get leave record
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

        //refresh work schedule record
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getleaverecords') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divLeaveRecord').html(data);
                $('#tableLeaveRecord').dataTable({

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

        //leave employee list table
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

        //search leave records
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchleaverecord') }}",
            method: "GET",
            data:{lastname:lname, firstname:fname, company:companyind, department:deptval, team:team, status:status}, 
            success:function(data){
                $("#divLeaveRecord").html(data);
                $('#tableLeaveRecord').dataTable({
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
    //end clear field

    //get leave type
    $(document).ready(function(){

        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getleavetype') }}",
            method: "GET",
            data:{},
            success:function(data){
                $("#cmbLeaveType").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end get leave type

    //get department
    $(document).ready(function(){
        $("#cmbLeaveType").change(function(){
            var ind = $(this).val();
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('getdays') }}",
                method: "GET",
                data:{id:ind}, 
                success:function(data){

                    $("#cmbLeaveDays").html(data);
                    $('#cmbLeaveDays').trigger("change");
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //end get department

    //add leave
    $(document).on("click", "#btnAdd", function(){

        var a = $('select[name=tableEmployeeList_length]').val();
        checkSelect();
        //check leave
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkleave') }}",
            method: "GET",
            data:$('#formLeave').serialize(),
            dataType: "json", 
            success:function(data){

                if(data.error.length > 0){

                    $("select[name=tableEmployeeList_length] option[value='"+ $('#tblCount').val() +"']").remove(); 
                    $('select[name=tableEmployeeList_length]').val(a).trigger('change');

                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want apply leave?") == true){

                        $('#btnSuccess').val("0 success");
                        $('#btnFail').val("0 failed");
                        $("#bodySuccess").html("");
                        $("#bodyFail").html("");

                        var res_success = "";
                        var res_fail = "";
                        
                        //add leave
                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('addleave') }}",
                            method: "POST",
                            data:$('#formLeave').serialize(),
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

                                alert("Leave Process Complete!");
                                refreshTable();
                                $('#leaveStatus').css("display", "block");
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
    //end add leave

    //cancel leave
    $(document).on("click", ".btnCancel", function(){

        var id = $(this).data("add");
        if(confirm("Are you sure you want to cancel leave to this employee?") == true){

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('cancelleave') }}",
                method: "POST",
                data: {id:id}, 
                success:function(data)
                {
                    alert("Leave successfully cancel!");
                    refreshTable();
                }
            }); 
        }
    });
    //end cancel leave


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

    //leave field
    $(document).on("click", "#leaveIcon", function(){
        if($("#hideLeaveField").is(":visible")){

            $("#leaveIcon").removeClass("fa-caret-square-o-down");
            $("#leaveIcon").addClass("fa fa-caret-square-o-right");
            $("#hideLeaveField").stop().slideUp(150);
        }
        else{

            $("#leaveIcon").removeClass("fa-caret-square-o-right");
            $("#leaveIcon").addClass("fa-caret-square-o-down");
            $("#hideLeaveField").stop().slideDown(150);
            
        }
    });

    //date format
    $(function (){
        $('#schedDate').datetimepicker({
            format: 'L'
        });
    });
</script>
@endsection