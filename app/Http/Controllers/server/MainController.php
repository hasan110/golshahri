<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User\User;
use App\Models\Advertise\Advertise;
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
      $confirmedAdvertisesCount = Advertise::whereConfirmed(0)->count();
      $advertisesCount = Advertise::all()->count();

      $todayViews = View::where('created_at' , '>' , Carbon::today())->count();
      $todayViewsFromHome = View::where('created_at' , '>' , Carbon::today())->where('type' , 'home')->count();
      $todayViewsFromAds = View::where('created_at' , '>' , Carbon::today())->where('type' , 'advertise')->count();
      $allViewsFromHome = View::where('type' , 'home')->count();
      $allViewsFromAds = View::where('type' , 'advertise')->count();
      
      return response()->json([
        'usersCount'=>$usersCount,
        'confirmedAdvertisesCount'=>$confirmedAdvertisesCount,
        'advertisesCount'=>$advertisesCount,
        'todayViews'=>$todayViews,
        'todayViewsFromHome'=>$todayViewsFromHome,
        'todayViewsFromAds'=>$todayViewsFromAds,
        'allViewsFromHome'=>$allViewsFromHome,
        'allViewsFromAds'=>$allViewsFromAds,
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
