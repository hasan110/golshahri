<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business\BusinessPicture;
use App\Models\Business\BusinessComment;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\Picture;
use App\Models\AppModels\Vote;
use App\Models\AppModels\View;
use App\Models\AppModels\Category;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function images(){
        return $this->hasMany(BusinessPicture::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function pictures(){
        return $this->morphMany(Picture::class, 'picturable');
    }
    public function views(){
        return $this->morphMany(View::class, 'viewable');
    }
    public function votes(){
        return $this->morphMany(Vote::class, 'votable');
    }
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }
}
