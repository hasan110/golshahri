<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User\User;
use App\Models\Advertise\Advertise;
use Illuminate\Support\Facades\Notification;

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
      
      return response()->json([
          'usersCount'=>$usersCount,
          'confirmedAdvertisesCount'=>$confirmedAdvertisesCount,
          'advertisesCount'=>$advertisesCount
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
