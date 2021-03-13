<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business\BusinessPicture;
use App\Models\Business\BusinessComment;
use App\Models\Business\Vote;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\View;
use App\Models\Category\Category;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images(){
        return $this->hasMany(BusinessPicture::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function views(){
        return $this->hasMany(View::class);
    }
    public function comments(){
        return $this->hasMany(BusinessComment::class);
    }
    public function votes(){
        return $this->hasMany(Vote::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
