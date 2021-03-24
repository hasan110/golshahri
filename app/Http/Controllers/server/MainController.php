<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User\User;
use App\Models\Advertise\Advertise;
use App\Models\Business\Business;
use Illuminate\Support\Facades\Notification;
use App\Models\AppModels\View;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class MainController extends Controller
{
    public function panelLoader(Request $request)
    {
        $adminNotifications = auth()->user()->unreadNotifications;
        $count = auth()->user()->unreadNotifications->count();
        // dd($adminNotifications);
        return view('server.template.template',compact('adminNotifications','count'));
    }
    public function getDashboardDetails(Request $request)
    {
      $usersCount = User::all()->count();
      $advertisesCount = Advertise::all()->count();
      $unConfirmedAdvertisesCount = Advertise::whereConfirmed(0)->count();
      $businessesCount = Business::all()->count();
      $unConfirmedBusinessesCount = Business::whereConfirmed(0)->count();

      $todayViews = View::where('created_at' , '>' , Carbon::today())->count();
      $todayViewsFromAdvertises = View::where('created_at' , '>' , Carbon::today())->where('type' , 'advertises')->count();
      $todayViewsFromAdvertise = View::where('created_at' , '>' , Carbon::today())->where('type' , 'advertise')->count();
      $todayViewsFromBusinesses = View::where('created_at' , '>' , Carbon::today())->where('type' , 'businesses')->count();
      $todayViewsFromBusiness = View::where('created_at' , '>' , Carbon::today())->where('type' , 'business')->count();
      
      return response()->json([
        'usersCount'=>$usersCount,
        'advertisesCount'=>$advertisesCount,
        'unConfirmedAdvertisesCount'=>$unConfirmedAdvertisesCount,
        'businessesCount'=>$businessesCount,
        'unConfirmedBusinessesCount'=>$unConfirmedBusinessesCount,
        'todayViews'=>$todayViews,
        'todayViewsFromAdvertises'=>$todayViewsFromAdvertises,
        'todayViewsFromAdvertise'=>$todayViewsFromAdvertise,
        'todayViewsFromBusinesses'=>$todayViewsFromBusinesses,
        'todayViewsFromBusiness'=>$todayViewsFromBusiness
        ] , 200);
      }
    public function notificationDelete(Request $request)
    {
      auth()->user()->notifications()->where('id', $request->id)->delete();
        
      return response()->json([
        'status'=>'ok',
        'message'=>'اعلانیه با موفقیت حذف شد .'
      ] , 200);
    }
}
