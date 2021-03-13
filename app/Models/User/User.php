<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Advertise\Advertise;
use App\Models\AppModels\View;
use App\Models\Business\Business;
use App\Models\Business\BusinessComment;
use App\Models\Business\Vote;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];
    protected $guard_name = 'web';
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function advertises(){
        return $this->hasMany(Advertise::class);
    }
    public function views(){
        return $this->hasMany(View::class);
    }
    public function businesses(){
        return $this->hasMany(Business::class);
    }
    public function business_comments(){
        return $this->hasMany(BusinessComment::class);
    }
    public function votes(){
        return $this->hasMany(Vote::class);
    }
}
