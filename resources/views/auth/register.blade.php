@extends('layouts.app')

@section('content')
<style>
    #tableUser{
        text-align:center;
    }
    #tableUser th{
        border-bottom:1px solid #000;
    }
    #empGroup{
        border: 1px solid #ddd;
        border-top: 5px solid #ddd;
        max-height: 200px;
        overflow-y:scroll; 
    }
</style>

<div id="pageContainer" class="container-fluid">
<h1>Create User</h1>
    <div id="moduleContainer" class="container-fluid">
        <div class="form-group">
                <label><b>NOTE: </b>On Reset Password the username of the employee is the default password after resetting</label>
            <input id="btnAdd" name="btnAdd" class="btn btn-sm button blue pull-right" type="button" value="Add User" style="width:150px;"/>
        </div>
        <br>

        <div id="divTableUser" class="table-responsive">
            <table id="tableUser" name="tableUser" class="table table-hover" cellspacing="0" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>User Name</th>
                        <th>User Type</th>  
                        <th style="width:200px"></th>
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- modal add user type --}}
<div id="modalUser_add" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 class="modal-title" ><b>Create User</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRegister" method="POST">
                @csrf

                    {{-- company --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbCompany" name="cmbCompany" class="form__field" placeholder="Company">
                            <option value="">Select Company</option>
                        </select>
                        <label for="cmbCompany" class="span-header form__label"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;Company</label>
                    </div>
                    <br>
                    {{-- department --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbDepartment" name="cmbDepartment" class="form__field" placeholder="Department">
                            <option value="">Select Department</option>
                        </select>
                        <span for="cmbDepartment" class="span-header form__label"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;Department</span>
                    </div>
                    <br>
                    {{-- lastname --}}
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtLastname" name = "txtLastname" type="text" class="form__field" placeholder="Lastname">
                        <label for="txtLastname" class="span-header form__label"><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;Lastname</label>
                    </div>
                    <br>
                    {{-- search --}}
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button id="btnSearch" type="button" class="btn btn-sm button blue pull-right" style="width:100px;">Search</button>
                        </div>
                    </div>
                    <hr>
                    <input id="empID" name="empID" type="hidden" value=""/>
                    <label><b>Employees With No User Access</b></label>
                    <div class="form-group row">
                        <div class="col-md">
                            <ul id="empGroup" class="list-group list-group-flush">
                                <li class="list-group-item">Search for employee</li>
                            </ul>
                        </div>
                    </div>
                    <br>
                    {{-- usertype --}}
                    <div class="form__group col-md-8 fg_margin">
                        <select id="cmbUserType_add" name="cmbUserType_add" class="form__field" placeholder="Company">
                            <option value="">Select User Type</option>
                        </select>
                        <label for="cmbUserType_add" class="span-header form__label"><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;User Type</label>
                    </div>
                    <br>

                    {{-- email address --}}
                    <input id="empName" name="empName" type="hidden" value=""/>
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtEmail_add" name = "txtEmail_add" type="text" class="form__field" placeholder="Email Address">
                        <label for="txtEmail_add" class="span-header form__label"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Email Address</label>
                    </div>
                    <br>

                    {{-- password --}}
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtPassword" name = "txtPassword" type="password" class="form__field" placeholder="Password">
                        <label for="txtPassword" class="span-header form__label"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Password</label>
                    </div>
                    <br>
                    
                    {{-- password --}}
                    <div class="form__group col-md-8 fg_margin">
                        <input id="txtConfirmPassword" name = "txtConfirmPassword" type="password" class="form__field" placeholder="Confirm Password">
                        <label for="txtConfirmPassword" class="span-header form__label"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Confirm Password</label>
                    </div>
                    <br>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
                <button id="btnSave" class="btn btn-sm button blue" type="button" style="width:70px;">Save</button>
            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}

{{-- edit modal --}}
<div id="modalUser_edit" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 class="modal-title" ><b>Edit User</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input id="oldEmail" type="hidden" value=""/>
                <input id="userID" type="hidden"value=""/>

                <div class="form-group row">
                    {{-- name --}}
                    <div class="col-md-6 text-md-center">
                        <label ><b>Name:</b></label>
                        <label id="lblUserName" value=""></label>
                    </div>
                    {{-- employee id --}}
                    <div class="col-md-6 text-md-center">
                        <label><b>Employee ID:</b></label>
                        <label id="lblEmployeeID" value=""></label>
                    </div>
                </div>
                <hr>
                {{-- usertype --}}
                <div class="form__group col-md-8 fg_margin">
                    <select id="cmbUserType_edit" name="cmbUserType_edit" class="form__field" placeholder="Department">
                        <option value="">- Select User Type -</option>
                    </select>
                    <span for="cmbUserType_edit" class="span-header form__label"><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;User Type</span>
                </div>
                <br>
                    
                {{-- email address --}}
                <div class="form__group col-md-8 fg_margin">
                    <input id="txtEmail_edit" name = "txtEmail_edit" type="text" class="form__field" placeholder="Email Address">
                    <label for="txtEmail_edit" class="span-header form__label"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Email Address</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
                <button id="btnUpdate" class="btn btn-sm button blue" type="button" style="width:70px;">Update</button>
            </div>
        </div>
    </div>
</div>
{{-- end edit modal --}}
    

<script>
    //get user
    refreshTable();
    function refreshTable(){
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getuser') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divTableUser').html(data);
                $('#tableUser').dataTable({

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
    //end get user

    //get employee lsit
    function refreshEmployeeList(){


        var company = $('#cmbCompany').val();
        var department = $('#cmbDepartment').val();
        var lastname = $('#txtLastname').val();

        var deptInfo = department.split("]]");
        var deptval = deptInfo[1];


        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('searchuser') }}",
            method: "GET",
            data:{company:company, department:deptval, lastname:lastname }, 
            success:function(data){
                $("#empGroup").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        $(document).on("click", ".radioemp ", function(){

            var empname = $(this).data("add");
            var empid = $(this).val();

            $("#empID").val(empid);
            $("#empName").val(empname);

        });
    }
    //end get employee lsit

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
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //end get department

    //get user type name
    $(document).ready(function(){
        var uid = $(this).val();
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getusertypename') }}",
            method: "GET",
            data:{}, 
            success:function(data){
                $("#cmbUserType_add").html(data);
                $("#cmbUserType_edit").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //end get user type name

    //search employee
    $(document).on("click", "#btnSearch", function(){

        refreshEmployeeList();
    });
    //end search employee

    //add user
    $(document).on("click", "#btnAdd", function(){

        clearField();
        $('#empGroup').empty();
        $('#empGroup').append('<li class="list-group-item">Search for employee</li>');
        $('#modalUser_add').modal('show');

    });

    $(document).on("click", "#btnSave", function(){
        
        var empID = $('#empID').val();
        var empName = $('#empName').val();
        var typeName = $('#cmbUserType_add').val();
        var email = $('#txtEmail_add').val();
        var password = $('#txtPassword').val();
        var conpassword = $('#txtConfirmPassword').val();

        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkuser_add') }}",
            method: "GET",
            data:{empName:empName, typeName:typeName, email:email, password:password, conpassword:conpassword},
            dataType: "json", 
            success:function(data){
                if(data.error.length > 0){
                    
                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want to add this user?") == true){

                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('register') }}",
                            method: "POST",
                            data: $('#formRegister').serialize(),
                            success:function(data){

                                clearField();
                                alert("User successfully added!");
                                refreshEmployeeList();
                                refreshTable();

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
    // end add user

    // edit user
    $(document).on("click", ".btnEdit", function(){

        var userInfo = $(this).data("add");
        var getUserInfo = userInfo.split("]]");

        $('#userID').val(userInfo[0]);
        $('#lblEmployeeID').html(getUserInfo[1]);
        $('#lblUserName').html(getUserInfo[2]);
        $('#txtEmail_edit').val(getUserInfo[3]);
        $('#oldEmail').val(getUserInfo[3]);
        document.getElementById("cmbUserType_edit").value = getUserInfo[4];

        $('#modalUser_edit').modal('show');

    });

    $(document).on("click", "#btnUpdate", function(){
        var id = $('#userID').val();
        var typeName = $('#cmbUserType_edit').val();
        var email = $('#txtEmail_edit').val();
        var oldEmail = $('#oldEmail').val();

        if(oldEmail == email && typeName != ""){
            if(confirm("Are you sure you want to update this user?") == true){

                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('edituser') }}",
                    method: "POST",
                    data: {id:id, typeName:typeName, email:email},
                    success:function(data){

                        alert("User successfully updated!");
                        refreshTable();
                        $('#modalUser_edit').modal('hide');
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }
        else{
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('checkuser_edit') }}",
                method: "GET",
                data:{typeName:typeName, email:email},
                dataType: "json", 
                success:function(data){
                    if(data.error.length > 0){
                        
                        alert(data.error[0]);
                    }
                    if(data.success.length > 0){
                        if(confirm("Are you sure you want to update this user?") == true){

                            $.ajax({
                                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url: "{{ route('edituser') }}",
                                method: "POST",
                                data: {id:id, typeName:typeName, email:email},
                                success:function(data){

                                    alert("User successfully updated!");
                                    refreshTable();
                                    $('#modalUser_edit').modal('hide');
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
        }
    })
    //end edit user

    // delete user
    $(document).on("click", ".btnDelete", function(){

        var userInfo = $(this).data("add");
        var getUserInfo = userInfo.split("]]");

        var id = getUserInfo[0];
        var email = getUserInfo[1];

        if(confirm("Are you sure you want to delete this User? " + email) == true){
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('deleteuser') }}",
                method: "POST",
                data:{id:id},
                success:function(data){

                    alert("User Type Successfully Deleted!");
                    refreshTable();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });  
        }
    });
    //end delete user
</script>

<script>
    function clearField()
    {
        $("#cmbCompany").val("");
        $("#cmbDepartment").val("");
        $("#txtLastname").val("");
        $("#cmbUserType_add").val("");
        $("#txtEmail_add").val("");
        $("#txtPassword").val("");
        $("#txtConfirmPassword").val("");

        $('#cmbCompany').trigger("change");
        $('#cmbDepartment').trigger("change");
    }
</script>

@endsection
