<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User\User;
use App\Models\AppModels\View;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateRandomCode() {
        $characters = "0123456789ABCDEFGHIJKL0123456789MNOPQRSTUVWXYZ0123456789";
        $shuffled = str_shuffle($characters);
        $randomString = substr($shuffled,0,5);
        return $randomString;
    }
    public function convertDate($date)
    {
        $date_array=explode('/',$date);
        $year = $date_array[0];
        $month = $date_array[1];
        $day = $date_array[2];
        if(strlen($month) == 1){
            $month = '0'.$month;
        }
        if(strlen($day) == 1){
            $day = '0'.$day;
        }
        $new_date_array = array($year,$month,$day);
        $new_date_string = implode('/',$new_date_array);
        $carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $new_date_string );
        
        return $carbon;
    }
    public function recordView($request , $type , $advertise_id , $business_id){
        $can_record_view = 0;

        if($request->has('auth_token')){
            $user = User::where('auth_token',$request->auth_token)->first();
            if($user){
                $user_id = $user->id;
            }else{
                $user_id = null;
            }
        }else{
            $user_id = null;
        }

        $ipAddress = $request->ip();

        $todayDate = Carbon::today();

        $check = View::where('advertise_id' , $advertise_id)
        ->where('business_id' , $business_id)
        ->where('user_ip' , $ipAddress)
        ->where('user_id' , $user_id)
        ->where('type' , $type)->latest()->first();

        if($check){

            if($check->created_at < $todayDate){
                $can_record_view = 1;
            }

        }else{

            $can_record_view = 1;

        }

        if($can_record_view){
            View::create([
                'user_id'=>$user_id,
                'user_ip'=>$ipAddress,
                'type'=>$type,
                'advertise_id'=>$advertise_id,
                'business_id'=>$business_id,
            ]);
        }

        return 'ok';
    }
}
