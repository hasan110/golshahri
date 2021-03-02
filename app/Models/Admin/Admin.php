<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Advertise\Advertise;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable,HasRoles;
    protected $guard_name = 'web';
    protected $guarded = [];

    public function advertises(){
        return $this->hasMany(Advertise::class);
    }
}
