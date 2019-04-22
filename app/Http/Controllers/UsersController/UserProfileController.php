<?php

namespace App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class UserProfileController extends Controller
{
    public function user_profile()
    {
        $data = array();

        $name = "";
        $position = "";
        $dept = "";
        $shift_sched = "";

        $user = auth()->user()->name; 

        $user_profile = DB::connection('mysql')->select("SELECT fullname,position,department from view_employee_information where company_id = '".auth()->user()->company_id."' ");
        $shift = DB::connection('mysql3')->select("SELECT * from employee_schedule as a left join schedule_template as b ON a.template_id = b.ind where a.company_id = '".auth()->user()->company_id."' ");
        
        if($shift[0]->type == "Regular Shift")
        {
            $shift_in = date("g:i:s A", strtotime($shift[0]->reg_in));
            $shift_out = date("g:i:s A", strtotime($shift[0]->reg_out));
            
            $shift_sched = $shift_in . " - " . $shift_out . " ( ";

            if($shift[0]->mon == "1")
            {
                $shift_sched .= "Mon ";
            }
            if($shift[0]->tue == "1")
            {
                $shift_sched .= "Tue ";
            }
            if($shift[0]->wed == "1")
            {
                $shift_sched .= "Wed ";
            }
            if($shift[0]->thu == "1")
            {
                $shift_sched .= "Thu ";
            }
            if($shift[0]->fri == "1")
            {
                $shift_sched .= "Fri ";
            }
            if($shift[0]->sat == "1")
            {
                $shift_sched .= "Sat ";
            }
            if($shift[0]->sun == "1")
            {
                $shift_sched .= "Sun ";
            }

            $shift_sched .= " )";
        }
        else if($shift[0]->type == "Irregular Shift")
        {
            $mon_shift_in = date("g:i:s A", strtotime($shift[0]->mon_in));
            $mon_shift_out = date("g:i:s A", strtotime($shift[0]->mon_out));

            $tue_shift_in = date("g:i:s A", strtotime($shift[0]->tue_in));
            $tue_shift_out = date("g:i:s A", strtotime($shift[0]->tue_out));

            $wed_shift_in = date("g:i:s A", strtotime($shift[0]->wed_in));
            $wed_shift_out = date("g:i:s A", strtotime($shift[0]->wed_out));

            $thu_shift_in = date("g:i:s A", strtotime($shift[0]->thu_in));
            $thu_shift_out = date("g:i:s A", strtotime($shift[0]->thu_out));

            $fri_shift_in = date("g:i:s A", strtotime($shift[0]->fri_in));
            $fri_shift_out = date("g:i:s A", strtotime($shift[0]->fri_out));

            $sat_shift_in = date("g:i:s A", strtotime($shift[0]->sat_in));
            $sat_shift_out = date("g:i:s A", strtotime($shift[0]->sat_out));

            $sun_shift_in = date("g:i:s A", strtotime($shift[0]->sun_in));
            $sun_shift_out = date("g:i:s A", strtotime($shift[0]->sun_out));

            $shift_sched .= "<br>";

            if($shift[0]->mon == "1")
            {
                $shift_mon = "Mon ";
                $shift_sched .= $shift_mon . "( " . $mon_shift_in . "-" .$mon_shift_out . " ) <br>";
            }
            if($shift[0]->tue == "1")
            {
                $shift_tue = "Tue ";
                $shift_sched .= $shift_tue . "( " . $tue_shift_in . "-" .$tue_shift_out . " ) <br>";
            }
            if($shift[0]->wed == "1")
            {
                $shift_wed = "Wed ";
                $shift_sched .= $shift_wed . "( " . $wed_shift_in . "-" .$wed_shift_out . " ) <br>";
            }
            if($shift[0]->thu == "1")
            {
                $shift_thu = "Thu ";
                $shift_sched .= $shift_thu . "( " . $thu_shift_in . "-" .$thu_shift_out . " ) <br>";
            }
            if($shift[0]->fri == "1")
            {
                $shift_fri = "Fri ";
                $shift_sched .= $shift_fri . "( " . $fri_shift_in . "-" .$fri_shift_out . " ) <br>";         
            }
            if($shift[0]->sat == "1")
            {
                $shift_sat = "Sat ";
                $shift_sched .= $shift_sat . "( " . $sat_shift_in . "-" .$sat_shift_out . " )";
            }
            if($shift[0]->sun == "1")
            {
                $shift_sun = "Sun ";
                $shift_sched .= $shift_sun . "( " . $sun_shift_in . "-" .$sun_shift_out . " )";
            }     
        }
        else if($shift[0]->type == "Flexi Shift")
        {
            if($shift[0]->mon == "1")
            {
                $shift_sched .= "Mon ";
            }
            if($shift[0]->tue == "1")
            {
                $shift_sched .= "Tue ";
            }
            if($shift[0]->wed == "1")
            {
                $shift_sched .= "Wed ";
            }
            if($shift[0]->thu == "1")
            {
                $shift_sched .= "Thu ";
            }
            if($shift[0]->fri == "1")
            {
                $shift_sched .= "Fri ";
            }
            if($shift[0]->sat == "1")
            {
                $shift_sched .= "Sat ";
            }
            if($shift[0]->sun == "1")
            {
                $shift_sched .= "Sun ";
            }
        }
        else
        {
            $shift_sched .= "Free Shift";
        }

        if(!empty($user_profile))
        {
            $name = $user_profile[0]->fullname;
            $position = $user_profile[0]->position;
            $dept = $user_profile[0]->department;
        }

        $data = array(
            'name'=>$name,
            'position'=>$position,
            'shift'=>$shift_sched,
            'dept'=>$dept
        );

        echo json_encode($data);
    }
}
