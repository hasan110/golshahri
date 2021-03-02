<?php

namespace App\Http\Controllers\server\role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User\Admin;
use App\Models\User\User;
use Morilog\Jalali\Jalalian;

class PermissionController extends Controller
{
    public function permissionList(Request $request)
    {
        $permissions = Permission::latest()->paginate(50);
        foreach($permissions as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d - H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d - H:i');
        }
        return response()->json($permissions,200);
    }
    public function permissionCreate(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:permissions'],
            'title' => ['required','unique:permissions'],
        ]);

        $permission = Permission::create([
            'name'=>$request->name,
            'title'=>$request->title,
        ]);

        return response()->json(
            [$permission , 'message'=>'ایجاد دسترسی با موفقیت انجام شد'],
            201
        );
    }
    public function permissionData(Request $request)
    {
        $permission = Permission::find($request->id);
        return response()->json($permission,200);
    }
    public function permissionEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'name' => ['required'],
            'title' => ['required'],
        ]);

        $permission = Permission::find($request->id);

        $permission->update([
            'name'=>$request->name,
            'title'=>$request->title
        ]);

        return response()->json(
            [$permission , 'message'=>'بروزرسانی با موفقیت انجام شد'],
            201
        );
    }
    public function permissionDelete(Request $request)
    {
        foreach($request->array as $id){
            $permission = Permission::find($id);
            foreach(Role::all() as $key=>$role){
                $role->revokePermissionTo($permission);
            }
            $permission->delete();
        }

        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
}
