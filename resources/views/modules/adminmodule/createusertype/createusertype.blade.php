@extends('layouts/app')

@section('content')
<style>
    #tableUserType, #tableManageAccess{
        text-align:center;
    }
    #tableUserType th, #tableManageAccess th{
        border-bottom:1px solid #000;
    }
</style>

<div id="pageContainer" class="container-fluid">
<h1>Create User Type</h1>
    <div id="moduleContainer" class="container-fluid">
        <div class="form-group">
            <input id="btnAdd" name="btnAdd" class="btn btn-sm button blue pull-right" type="button" value="Add User Type" style="width:150px;"/>
        </div>
        <br><br>

        <div id="divTableUserType" class="table-responsive">
            <table id="tableUserType" name="tableUserType" class="table table-hover" cellspacing="0" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>User Type Name</th>
                        <th>Description</th>
                        <th style="width:200px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
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
<div id="modalUserType_add" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 class="modal-title" ><b>Create User Type</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form__group col-md-8 fg_margin">
                    <input type="text" id="txtUserTypeName_add" name = "txtUserTypeName_add" class="form__field" placeholder="User Type Name">
                    <label for="txtUserTypeName_add" class="span-header form__label"><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;User Type Name</label>
                </div>
                <br>
                <div class="form__group col-md-8 fg_margin">
                    <textarea id="txtDescription_add" name = "txtDescription_add" class="form__field" placeholder="Description"></textarea>
                    <label for="txtDescription_add" class="span-header form__label"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Description</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
                <button id="btnSave" class="btn btn-sm button blue" type="button" style="width:70px;">Save</button>
            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}

{{-- modal edit user type --}}
<div id="modalUserType_edit" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Edit User Type</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="oldUserTypeName" value=""/>
                <input type="hidden" id="UTID" value=""/>

                {{-- lastname --}}
                <div class="form__group col-md-8 fg_margin">
                    <input id="txtUserTypeName_edit" name = "txtUserTypeName_edit" type="text" class="form__field" placeholder="User Type Name">
                    <label for="txtUserTypeName_edit" class="span-header form__label"><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;User Type Name</label>
                </div>
                <br>

                <div class="form__group col-md-8 fg_margin">
                    <textarea id="txtDescription_edit" name = "txtDescription_edit" class="form__field" placeholder="Description"></textarea>
                    <label for="txtDescription_edit" class="span-header form__label"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Description</label>
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

<!-- manage access -->
<div id="modalManageAccess" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg" style="border:0px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Manage Access</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="formManage">
                <input type="hidden" id="UID" name="UID" value=""/>

                <div id="divTableManageAccess" class="table-responsive">
                    <table id="tableManageAccess" name="tableManageAccess" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Module Name</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm button gray" data-dismiss="modal" style="width:70px;">Close</button>
                <button id="btnUpdateAccess" class="btn btn-sm button blue" type="button" style="width:120px;">Update Access</button>
            </div>
        </div>
    </div>
</div>
<!-- end manage access -->

<script>




</script>


<script>

    refreshTable();
    function refreshTable(){
        //get user type
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getusertype') }}",
            method: "GET",
            data:{},
            success:function(data){
                $('#divTableUserType').html(data);
                $('#tableUserType').dataTable({

                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                }); 
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        //end get user type
    }


    // save user type
    $(document).on("click", "#btnAdd", function(){

        $('#modalUserType_add').modal('show');
        $('#txtUserTypeName_add').val("");
        $('#txtDescription_add').val("");

    });

    $(document).on("click", "#btnSave", function(){

        var typeName = $('#txtUserTypeName_add').val();
        var typeDesc = $('#txtDescription_add').val();

        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('checkusertype') }}",
            method: "GET",
            data:{typeName:typeName},
            dataType: "json",
            success:function(data){
                if(data.error.length > 0){
                    
                    alert(data.error[0]);
                }
                if(data.success.length > 0){
                    if(confirm("Are you sure you want to add this user type?") == true){

                        $.ajax({
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('saveusertype') }}",
                            method: "POST",
                            data:{typeName:typeName, typeDesc:typeDesc},
                            success:function(data){

                                alert("User Type Name successfully added!");
                                refreshTable();

                                $('#txtUserTypeName_add').val("");
                                $('#txtDescription_add').val("");

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
    // end save user type

    // edit user type
    $(document).on("click", ".btnEdit", function(){

        var typeInfo = $(this).data("add");
        var getTypeInfo = typeInfo.split("]]");

        $('#modalUserType_edit').modal('show');
        $('#txtUserTypeName_edit').val(getTypeInfo[0]);
        $('#txtDescription_edit').val(getTypeInfo[1]);
        $('#UTID').val(getTypeInfo[2]);
        $('#oldUserTypeName').val(getTypeInfo[0]);

    });

    $(document).on("click", "#btnUpdate", function(){

        var typeName = $('#txtUserTypeName_edit').val();
        var typeDesc = $('#txtDescription_edit').val();
        var id = $('#UTID').val();
        var oldTypeName = $('#oldUserTypeName').val();

        if(oldTypeName == typeName){
            if(confirm("Are you sure you want to update this user type?") == true){

                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('editusertype') }}",
                    method: "POST",
                    data:{id:id, typeName:typeName, typeDesc:typeDesc, oldTypeName:'0'},
                    success:function(data){

                        alert("User Type Name Successfully Updated!");
                        refreshTable();
                        
                        $('#txtUserTypeName_edit').val("");
                        $('#txtDescription_edit').val("");
                        $('#modalUserType_edit').modal('hide');

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
                url: "{{ route('checkusertype') }}",
                method: "GET",
                data:{typeName:typeName},
                dataType: "json",
                success:function(data){
                    if(data.error.length > 0){
                        
                        alert(data.error[0]);
                    }
                    if(data.success.length > 0){
                        if(confirm("Are you sure you want to update this user type?") == true){

                            $.ajax({
                                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url: "{{ route('editusertype') }}",
                                method: "POST",
                                data:{id:id, typeName:typeName, typeDesc:typeDesc, oldTypeName:'1'},
                                success:function(data){

                                    alert("User Type Name Successfully Updated!");
                                    refreshTable();

                                    $('#txtUserTypeName_edit').val("");
                                    $('#txtDescription_edit').val("");
                                    $('#modalUserType_edit').modal('hide');

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
    });
    // end edit user type

    //manage access
    $(document).on("click", ".btnManage", function(){

        var uid = $(this).data("add");

        $('#UID').val(uid);
        $('#modalManageAccess').modal('show');

        //get manage access
        $.ajax({
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getmanageaccess') }}",
            method: "GET",
            data:{uid:uid},
            success:function(data){
                $('#divTableManageAccess').html(data);
                $('#tableManageAccess').dataTable({

                    "serverSide": false, 
                    "retrieve": true, 
                    "ordering": false
                }); 
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        //end get manage access
    });

    $(document).on("click", "#btnUpdateAccess", function(){

        if(confirm("Are you sure you want to update his/her user access?") == true){

            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('updateaccess') }}",
                method: "POST",
                data:$('#formManage').serialize(),
                success:function(data){

                    alert("User Manage Access Successfully Updated!");

                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }


    });

    //end manage access

    // delete user type
    $(document).on("click", ".btnDelete", function(){

        var typeInfo = $(this).data("add");
        var getTypeInfo = typeInfo.split("]]");

        var id = getTypeInfo[0];
        var typeName = getTypeInfo[1];

        if(confirm("Are you sure you want to delete " + typeName + "?"+ "\nWarning : All user with this type will be deleted.") == true){
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('deleteusertype') }}",
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
    // end delete user type
</script>
@endsection