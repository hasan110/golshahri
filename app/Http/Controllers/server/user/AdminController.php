<?php

namespace App\Http\Controllers\server\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User\User;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Morilog\Jalali\Jalalian;

class AdminController extends Controller
{
    public function adminList(Request $request)
    {
        $admins = Admin::latest()->paginate(50);
        foreach($admins as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d - H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d - H:i');
        }
        $roles = Role::all();
        return response()->json(['admins'=>$admins,'roles'=>$roles],200);
    }
    public function adminCreate(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required','unique:admins'],
            'username' => ['required','unique:admins'],
            'number' => ['required','unique:admins'],
            'password' => ['required'],
            'role' => ['required'],
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/admin',$file_name);
            $admin_image = 'admin/'.$file_name;
        }else{
            $admin_image = null;
        }

        $admin = Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'username'=>$request->username,
            'number'=>$request->number,
            'password'=>Hash::make($request->password),
            'unhashed_password'=>$request->password,
            'is_block'=>$request->is_block,
            'image'=>$admin_image
        ]);
        $admin->assignRole($request->role);
        return response()->json(
            [$admin , 'message'=>'ایجاد مدیر با موفقیت انجام شد'],
            201
        );
    }
    public function adminData(Request $request)
    {
        $admin = Admin::find($request->id);
        $admin['adminRole'] = ($admin->roles->first() ? $admin->roles->first()->name : '');
        return response()->json($admin,200);
    }
    public function adminEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'name' => ['required'],
            'email' => ['required'],
            'username' => ['required'],
            'number' => ['required']
        ]);

        $admin = Admin::find($request->id);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/admin',$file_name);
            File::delete(public_path().'/uploads/'.$admin->image);
            $admin_image = 'admin/'.$file_name;
        }else{
            $admin_image = $admin->image;
        }

        $admin->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'username'=>$request->username,
            'number'=>$request->number,
            'password'=>Hash::make($request->unhashed_password),
            'unhashed_password'=>$request->unhashed_password,
            'is_block'=>$request->is_block,
            'image'=>$admin_image
        ]);
        $admin->syncRoles($request->adminRole);
        return response()->json(
            [$admin , 'message'=>'ویرایش مدیر با موفقیت انجام شد'],
            201
        );
    }
    public function adminDelete(Request $request)
    {
        foreach($request->array as $id){
            $admin = Admin::find($id);
            if($admin->image){
                File::delete(public_path().'/uploads/'.$admin->image);
            }
            $admin->delete();
        }

        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
    public function adminchangeBlockStatus(Request $request)
    {
        $admin = Admin::find($request->admin_id);
        if($admin->is_block == 1){
            $is_block = 0;
        }else{
            $is_block = 1;
        }
        $admin->update(['is_block'=>$is_block]);

        return response()->json(
            ['message'=>'مدیر با موفقیت تغییر وضعیت داده شد'],
            200
        );
    }
    public function adminProfile(Request $request)
    {
        $admin = Auth::user();
        return response()->json(
            $admin,
            200
        );
    }
}
