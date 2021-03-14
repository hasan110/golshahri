<?php

namespace App\Models\Advertise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertise\AdvertisePicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\View;
use App\Models\Category\Category;
use App\Models\Region\Region;

class Advertise extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function images(){
        return $this->hasMany(AdvertisePicture::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function views(){
        return $this->hasMany(View::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
}
