<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\UserType;
use App\Models\UserModuleAccess;

class CreateUserTypeController extends Controller
{
    public function index()
    {
        return view ('modules.adminmodule.createusertype.createusertype');
    }

    public function getUserType()
    {
        $data = "";

        $query = "SELECT id, type_name, type_description FROM user_type WHERE deleted ='0' ORDER BY type_name";
        $select_query = DB::connection('mysql')->select($query);

        $data .= '<table id="tableUserType" name="tableUserType" class="table table-hover" cellspacing="0" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>User Type Name</th>
                            <th>Description</th>
                            <th style="width:200px"></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        if(count($select_query) > 0){
            foreach($select_query as $info){
                $data .= '<tr>';
                $data .= '<td>' . $i ."</td>";
                $data .= '<td>' . $info->type_name .'</td>';
                $data .= '<td>' . $info->type_description .'</td>';
                $data .= '<td><button class="btn btn-sm button blue btnEdit" type="button" data-add="'. $info->type_name .']]'. $info->type_description .']]'. $info->id .'">Edit</button>';
                $data .= '<button class="btn btn-sm button yellow btnManage" type="button" data-add="' . $info->id . '"style="margin:5px">Manage Access</button>';
                $data .= '<button class="btn btn-sm button red btnDelete" type="button" data-add="' . $info->id . ']]' . $info->type_name . '">Delete</button></td>';
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>';
        }
        echo $data;
    }
    public function checkUserType(Request $request)
    {     
        $result = array();
        $error = array();
        $success = array();

        $query = "SELECT type_name FROM user_type WHERE type_name = '" . $request->typeName . "'";
        $select_query = DB::connection('mysql')->select($query);

        if($request->typeName == ""){
            
            $message = "User Type Name is required!";
            $error[] = $message;

        }
        else if(!empty($select_query)){

            $message = "User Type Name already exist!";
            $error[] = $message;

        }
        else{
            
            $message = "1";
            $success[] = $message;
        }

        $result = array(
            'error'=>$error,
            'success'=>$success
        );
        echo json_encode($result);
    }

    public function saveUserType(Request $request)
    {

        $insert_utype = new UserType;
        $insert_utype->type_name = $request->typeName;
        $insert_utype->type_description = $request->typeDesc;
        $insert_utype->created_by = auth()->user()->name;
        $insert_utype->save();

        $user_type_id = $insert_utype->id;

        $insert_access = new UserModuleAccess;
        $insert_access->user_type_id = $user_type_id;
        $insert_access->created_by = auth()->user()->name;
        $insert_access->lu_by = auth()->user()->name;
        $insert_access->save();
    }

    public function editUserType(Request $request)
    {

        $update_query = UserType::find($request->id);
        $update_query->type_name = $request->typeName;
        $update_query->type_description = $request->typeDesc;
        $update_query->lu_by = auth()->user()->name;
        $update_query->save();

    }

    public function getManageAccess(Request $request)
    {
        $data = "";

        $module_query = "SELECT * FROM user_modules WHERE module_type = 'module' AND deleted ='0' ORDER BY module_name";
        $select_module = DB::connection('mysql')->select($module_query);
    
        $data .= '<table id="tableManageAccess" name="tableManageAccess" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Module Name</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        $i = 1;
        $moduleRow[] = '';
        if(count($select_module) > 0){
            foreach($select_module as $um){

                $access_query = "SELECT " . $um->module_code . " AS module_acess from user_module_access WHERE user_type_id ='" . $request->uid . "'";
                $select_access = DB::connection('mysql')->select($access_query);
                $moduleRow[$um->module_code] = $select_access[0]->module_acess;

                $none = "";
                $all = "";
                $view = "";
                $add = "";
                $edit = "";
                $delete = "";
                
                if($moduleRow[$um->module_code] == "all"){
                    $all = "checked";
                }
                else if($moduleRow[$um->module_code] == "view"){
                    $view = "checked";
                }
                else if($moduleRow[$um->module_code] == "add"){
                    $add = "checked";
                } 
                else if($moduleRow[$um->module_code] == "edit"){
                    $edit = "checked";
                } 
                else if($moduleRow[$um->module_code] == "delete"){
                    $delete = "checked";
                } 
                else if($moduleRow[$um->module_code] == "none"){
                    $none = "checked";
                }   

                $data .= '<tr>';
                $data .= '<td>' . $i ."</td>";
                $data .= '<td>' . $um->module_name .'</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="none"' . $none . '/> No Access</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="all"' . $all . '/> All Access</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="view"' . $view . '/> View Only</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="add"' . $add . '/> Add Only</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="edit"' . $edit .'/> With Edit</td>';
                $data .= '<td><input name="' . $um->module_code . '"type="radio" value="delete"' . $delete . '/> With Delete</td>';
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>';
        }
        echo $data;
    }

    public function updateAccess(Request $request)
    {

        $module_query = "SELECT * FROM user_modules WHERE module_type = 'module' AND deleted ='0' ORDER BY module_name";
        $select_module = DB::connection('mysql')->select($module_query);
        $i = 1;
        foreach($select_module as $um){

            $valType = $request->get($um->module_code);

            $update_access = DB::connection("mysql")->select("UPDATE user_module_access SET ". $um->module_code ." = '" . $valType . "' WHERE user_type_id = '" . $request->input('UID') . "'");
            $i++;
        }
    }

    public function deleteUserType(Request $request)
    {
        $delete_utype = UserType::find($request->id);
        $delete_utype->lu_by = auth()->user()->name;
        $delete_utype->deleted = '1';
        $delete_utype->save();

        //$update_access = DB::connection("mysql")->select("UPDATE user_module_access SET ". $um->module_code ." = '" . $valType . "' WHERE user_type_id = '" . $request->input('UID') . "'");


    }
}
