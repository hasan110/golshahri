<?php

namespace App\Http\Controllers\server\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\Admin\Admin;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegistered;

class UserController extends Controller
{
    public function userList(Request $request)
    {
        $users = User::latest()->paginate(50);
        foreach($users as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($users,200);
    }
    public function userCreate(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'number' => ['required','unique:users'],
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/users',$file_name);
            $user_image = 'users/'.$file_name;
        }else{
            $user_image = null;
        }

        $auth_token = md5(rand(1000,9999).time());

        $user = User::create([
            'name'=>$request->name,
            'number'=>$request->number,
            'is_block'=>$request->is_block,
            'image'=>$user_image,
            'auth_token'=>$auth_token
        ]);

        $notifiable_admins = Admin::role('super_admin')->get(); 

        Notification::send($notifiable_admins , new NewUserRegistered($user));

        return response()->json(
            [$user , 'message'=>'ایجاد کاربر با موفقیت انجام شد'],
            201
        );
    }
    public function userData(Request $request)
    {
        $user = User::find($request->id);
        return response()->json($user,200);
    }
    public function userEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'name' => ['required'],
            'number' => ['required'],
        ]);

        $user = User::find($request->id);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/users',$file_name);
            File::delete(public_path().'/uploads/'.$user->image);
            $user_image = 'users/'.$file_name;
        }else{
            $user_image = $user->image;
        }

        $user->update([
            'name'=>$request->name,
            'number'=>$request->number,
            'is_block'=>$request->is_block,
            'image'=>$user_image
        ]);

        return response()->json(
            [$user , 'message'=>'ویرایش کاربر با موفقیت انجام شد'],
            201
        );
    }
    public function userDelete(Request $request)
    {
        foreach($request->array as $id){
            $user = User::find($id);
            if($user->image){
                File::delete(public_path().'/uploads/'.$user->image);
            }
            $user->delete();
        }

        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
    public function userChangeBlockStatus(Request $request)
    {
        $user = User::find($request->user_id);
        if($user->is_block == 1){
            $is_block = 0;
        }else{
            $is_block = 1;
        }
        $user->update(['is_block'=>$is_block]);

        return response()->json(
            ['message'=>'کاربر با موفقیت تغییر وضعیت داده شد'],
            200
        );
    }
}
