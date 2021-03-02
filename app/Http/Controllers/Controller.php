<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
}
