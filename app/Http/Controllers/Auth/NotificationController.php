<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;


class NotificationController extends Controller
{
    public function viewnotifications(Request $request){
        $data = "";

        $query = "SELECT * FROM events ORDER BY created_by DESC"; //, status DESC
        $select_query = DB::connection('mysql3')->select($query);

        if(count($select_query) > 0){
            foreach($select_query as $info){
                if($info->status == 0){
                    $data .= '<a class="dropdown-item"><small><b>'.$info->title.'</b></small>
                    <br>
                    <small><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p></small>
                    
                    <small><em>'.date("F j Y H:i:s A",strtotime($info->created)).'</em></small>
                    </a>';
                }
                else if($info->status == 1){
                    $data .= '<a class="dropdown-item"><small><b>'.$info->title.'</b></small>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-success">new</span>
                    <br>
                    <small><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p></small>
                    
                    <small><em>'.date("F j Y H:i:s A",strtotime($info->created)).'</em></small>
                    </a>';
                }
            }
        }
        else{
            $data .= '<a class="dropdown-item" href="#">No Announcement(s)</a>';
        }


        echo $data;
    }
    public function numnotifications(Request $request){
        $data = "";
        $query = "SELECT status FROM events WHERE status = 1";
        $select_query = DB::connection('mysql3')->select($query);
        $numNotification = count($select_query);
        
        if($numNotification >= 1){
            $data .= '<span class="fa fa-comment"></span><span class="num">'.$numNotification.'</span>';
            echo $data;
        }
    }
}