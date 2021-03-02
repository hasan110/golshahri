<?php

use App\Models\User\User;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));


        //insert in to package tables all permissions and admin
        $admin = Admin::create([
            'name'=>'admin',
            'email'=>'golshahri.dev@gmail.com',
            'username'=>'golshahri',
            'password'=>Hash::make('12345'),
            'unhashed_password'=>'12345',
            'number'=>'09307818757',
        ]);
        $role = Role::create([
            'name' => 'super_admin'
        ]);
        DB::table('permissions')->insert([
            ['name' => 'menu_advertise','title' => 'منوی آگهی'],
            ['name' => 'create_advertise','title' => 'ایجاد آگهی'],
            ['name' => 'edit_advertise','title' => 'ویرایش آگهی'],
            ['name' => 'delete_advertise','title' => 'حذف آگهی'],
            ['name' => 'change_status_advertise','title' => 'تغییر وضعیت آگهی'],
            ['name' => 'menu_user','title' => 'منوی کاربر'],
            ['name' => 'create_user','title' => 'ایجاد کاربر'],
            ['name' => 'edit_user','title' => 'ویرایش کاربر'],
            ['name' => 'delete_user','title' => 'حذف کاربر'],
            ['name' => 'change_status_user','title' => 'تغییر وضعیت کاربر'],
            ['name' => 'menu_admin','title' => 'منوی مدیر'],
            ['name' => 'create_admin','title' => 'ایجاد مدیر'],
            ['name' => 'edit_admin','title' => 'ویرایش مدیر'],
            ['name' => 'delete_admin','title' => 'حذف مدیر'],
            ['name' => 'change_status_admin','title' => 'تغییر وضعیت مدیر'],
            ['name' => 'menu_setting','title' => 'منوی تنظیمات'],
            ['name' => 'edit_setting','title' => 'ویرایش تنظیمات'],
            ['name' => 'menu_permission','title' => 'منوی دسترسی'],
            ['name' => 'create_permission','title' => 'ایجاد دسترسی'],
            ['name' => 'edit_permission','title' => 'ویرایش دسترسی'],
            ['name' => 'delete_permission','title' => 'حذف دسترسی'],
            ['name' => 'menu_role','title' => 'منوی نقش'],
            ['name' => 'create_role','title' => 'ایجاد نقش'],
            ['name' => 'edit_role','title' => 'ویرایش نقش'],
            ['name' => 'delete_role','title' => 'حذف نقش'],
        ]);
        $all_permissions = [
            'menu_advertise',
            'create_advertise',
            'edit_advertise',
            'delete_advertise',
            'change_status_advertise',
            'menu_user',
            'create_user',
            'edit_user',
            'delete_user',
            'change_status_user',
            'menu_admin',
            'create_admin',
            'edit_admin',
            'delete_admin',
            'change_status_admin',
            'menu_setting',
            'edit_setting',
            'menu_permission',
            'create_permission',
            'edit_permission',
            'delete_permission',
            'menu_role',
            'create_role',
            'edit_role',
            'delete_role',
        ];
        $role->givePermissionTo($all_permissions);
        $admin->assignRole('super_admin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
