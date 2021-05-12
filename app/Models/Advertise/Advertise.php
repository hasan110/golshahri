<?php

namespace App\Models\Advertise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertise\AdvertisePicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\Picture;
use App\Models\AppModels\View;
use App\Models\AppModels\Category;
use App\Models\AppModels\Region;
use App\Models\AppModels\Comment;
use App\Models\AppModels\Vote;

class Advertise extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function images(){
        return $this->hasMany(AdvertisePicture::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function pictures(){
        return $this->morphMany(Picture::class, 'picturable');
    }
    public function views(){
        return $this->morphMany(View::class, 'viewable');
    }
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function votes(){
        return $this->morphMany(Vote::class, 'votable');
    }
}
