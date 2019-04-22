<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use App\Post;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // 'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function register(Request $request)
    {

        event(new Registered($user = $this->create($request->all())));
    }

    protected function create(array $data)
    {
        return User::create([
            'company_id' => $data['empID'],
            'user_type_id' => $data['cmbUserType_add'],
            'name' => $data['empName'],
            'email' => $data['txtEmail_add'],
            'password' => Hash::make($data['txtPassword']),
            'lu_by' => auth()->user()->name,
            'created_by' => auth()->user()->name,
        ]);
    }

    public function getUser()
    {
        $data = "";

        $query = "SELECT id, company_id, fullname, email, user_type_id, type_name FROM view_user";
        $select_query = DB::connection('mysql')->select($query);

        $data .= '<table id="tableUser" name="tableUser" class="table table-hover" cellspacing="0" style="width:100%">
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
                    <tbody>';
        $i = 1;
        if(!empty($select_query) > 0){
            foreach($select_query as $info){
                $data .= '<tr>';
                $data .= '<td>' . $i ."</td>";
                $data .= '<td>' . $info->company_id .'</td>';
                $data .= '<td>' . $info->fullname .'</td>';
                $data .= '<td>' . $info->email .'</td>';
                $data .= '<td>' . $info->type_name .'</td>';
                $data .= '<td><button class="btn btn-sm button blue btnEdit" type="button" data-add="' . $info->id . ']]' . $info->company_id . ']]' . $info->fullname . ']]' . $info->email . ']]' . $info->user_type_id . '">Edit</button>';
                $data .= '<button class="btn btn-sm button yellow btnReset" type="button" style="margin:5px">Reset Password</button>';
                $data .= '<button class="btn btn-sm button red btnDelete" type="button" data-add="' . $info->id . ']]' . $info->email . '">Delete</button></td>';
                $data .= '</tr>';
                $i++;
            }
            $data .= '</tbody>
                    </table>';
        }
        echo $data;
    }

    public function getCompany()
    {
        $data = "";

        $query = "SELECT id, company_name FROM company WHERE active ='yes' ORDER BY company_name";
        $select_query = DB::connection('mysql3')->select($query);

        $data .= '<option value="">Select Company</option>';

        if(count($select_query) > 0){
            foreach($select_query as $info){   
                
                $data .= '<option value="' . $info->id .'">' . $info->company_name .'</option>';
            }
        }
        echo $data;
    }

    public function getDepartment(Request $request)
    {
        $data = "";

        $query = "SELECT ind, department_name FROM department WHERE active = 'yes' AND company_ind ='" . $request->ind . "' ORDER BY department_name";
        $select_query = DB::connection('mysql3')->select($query);

        $data .= '<option value="">Select Department</option>';
        if(count($select_query) > 0){
            foreach($select_query as $info){   
                
                $data .= '<option value="'. $info->ind . ']]' . $info->department_name . '">' . $info->department_name .'</option>';
            }
        }
        echo $data;
    }
    
    public function getUserTypeName()
    {
        $data = "";

        $query = "SELECT id, type_name FROM user_type WHERE deleted ='0' ORDER BY type_name";
        $select_query = DB::connection('mysql')->select($query);

        $data .= '<option value="">Select User Type</option>';
        if(count($select_query) > 0){
            foreach($select_query as $info)
            {
                $data .= '<option value="' . $info->id . '">' . $info->type_name .'</option>'; 
            }
        }
        echo $data;
    }

    public function searchUser(Request $request)
    {
        $data = "";

        $query = "SELECT a.company_id,CONCAT(a.lname,', ',a.fname) AS empname,b.company_ind,b.department FROM personal_information AS a LEFT JOIN employee_information AS b ON a.company_id = b.company_id";
        if($request->company == "" && $request->department == "" && $request->lastname == ""){

            $query .= " WHERE a.active ='yes' AND a.company_id NOT IN (SELECT company_id FROM timekeeping_system.users) AND a.company_id ORDER BY a.lname";
        }
        else{
            if($request->company == ""){

                $query .= " WHERE a.active ='yes' AND a.lname LIKE '%$request->lastname%' AND a.company_id NOT IN (SELECT company_id FROM timekeeping_system.users) AND a.company_id ORDER BY a.lname";
            }
            else{
                if($request->department == ""){

                    $query .= " WHERE a.active ='yes' AND b.company_ind = '$request->company' AND a.lname LIKE '%$request->lastname%' and a.company_id NOT IN (SELECT company_id FROM timekeeping_system.users) AND a.company_id ORDER BY a.lname";
                }
                else{

                    $query .= " WHERE a.active ='yes' AND b.department ='$request->department' AND b.company_ind = '$request->company' AND a.lname LIKE '%$request->lastname%' AND a.company_id NOT IN (SELECT company_id FROM timekeeping_system.users) AND a.company_id ORDER BY a.lname";
                }
            }
        }
        $select_query = DB::connection('mysql3')->select($query);

        $i = 1;
        if(count($select_query) > 0){
            foreach($select_query as $info){  

                $data .= '<li class="list-group-item">';
                $data .= '<input id="rdSelectEmp' . $i . '"name="rdSelectEmp" class="radioemp" type="radio" value="' . $info->company_id . '" data-add="'. $info->empname . '"/> ' . $info->empname . ' - ' . $info->department . '</input>';
                $data .= '</li>';
                $i++;
            }
        }
        else{

            $data .= '<li class="list-group-item">No Employees Found</li>';
        }
        echo $data;
    }

    public function checkUser_add(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        $query = "SELECT email FROM users WHERE email = '" . $request->email . "'";
        $select_query = DB::connection('mysql')->select($query);
        if($request->empName == ""){

            $message = "Please select employee!";
            $error[] = $message;
        }
        else if($request->typeName == ""){

            $message = "Please select a User Type!";
            $error[] = $message;
        }
        else if($request->email == ""){

            $message = "Email Address field is required!";
            $error[] = $message;
        }
        else if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){

            $message = "Email Address is not valid!";
            $error[] = $message;
        }
        else if(!empty($select_query)){

            $message = "Emaill Address already exist!";
            $error[] = $message;
        }
        else if($request->password == ""){

            $message = "Password field is required!";
            $error[] = $message;
        }
        else if($request->conpassword == ""){

            $message = "Confirm Password field is required!";
            $error[] = $message;
        }
        else if($request->password != $request->conpassword){

            $message = "Pssword mismatch!";
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

    public function checkUser_edit(Request $request)
    {
        $result = array();
        $error = array();
        $success = array();

        $query = "SELECT email FROM users WHERE email = '" . $request->email . "'";
        $select_query = DB::connection('mysql')->select($query);
        if($request->typeName == ""){

            $message = "Please select a User Type!";
            $error[] = $message;
        }
        else if($request->email == ""){

            $message = "Email Address field is required!";
            $error[] = $message;
        }
        else if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){

            $message = "Email Address is not valid!";
            $error[] = $message;
        }
        else if(!empty($select_query)){

            $message = "Emaill Address already exist!";
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

    public function editUser(Request $request)
    {
        $update_query = User::find($request->id);
        $update_query->user_type_id = $request->typeName;
        $update_query->email = $request->email;
        $update_query->lu_by = auth()->user()->name;
        $update_query->save();
    }

    public function deleteUser(Request $request)
    {
        $delete_query = User::find($request->id);
        $delete_query->lu_by = auth()->user()->name;
        $delete_query->active = 'no';
        $delete_query->save();
    }
}
