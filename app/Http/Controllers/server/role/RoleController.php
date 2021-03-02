<?php

namespace App\Http\Controllers\server\role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User\Admin;
use App\Models\User\User;
use Morilog\Jalali\Jalalian;

class RoleController extends Controller
{
    public function roleList(Request $request)
    {
        $roles = Role::latest()->with('permissions')->paginate(50);
        foreach($roles as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d - H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d - H:i');
        }
        $permissions = Permission::latest()->get();
        return response()->json(['roles'=>$roles,'permissions'=>$permissions],200);
    }
    public function roleCreate(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:roles'],
            'permissions' => ['required']
        ]);

        $role = Role::create([
            'name'=>$request->name
        ]);
        $role->givePermissionTo($request->permissions);
        return response()->json(
            [$role , 'message'=>'ایجاد نقش با موفقیت انجام شد'],
            201
        );
    }
    public function roleData(Request $request)
    {
        $role = Role::find($request->id);
        $permissions = $role->permissions->pluck('name')->toArray();
        $role['rolePermissions'] = $permissions;
        return response()->json($role,200);
    }
    public function roleEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'name' => ['required']
        ]);

        $role = Role::find($request->id);

        $role->update([
            'name'=>$request->name
        ]);

        $allPermissions = Permission::latest()->pluck('name')->toArray();
        $role->revokePermissionTo($allPermissions);
        $role->givePermissionTo($request->rolePermissions);

        return response()->json(
            [$role , 'message'=>'بروزرسانی با موفقیت انجام شد'],
            201
        );
    }
    public function roleDelete(Request $request)
    {
        $isSuperAdminInArray = false;
        foreach($request->array as $id){
            $role_name = Role::find($id)->name;
            if($role_name == 'super_admin'){
                $isSuperAdminInArray = true;
            }
        }
        if($isSuperAdminInArray){
            return response()->json(
                ['error'=>'نقش super_admin نمی تواند حذف شود'],
                400
            );
        }else{
            $allPermissions = Permission::latest()->pluck('name')->toArray();
            foreach($request->array as $id){
                $role = Role::find($id);
                $role->revokePermissionTo($allPermissions);
                $role->delete();
            }
        }

        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
}