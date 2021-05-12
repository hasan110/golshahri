<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business\Business;
use App\Models\Advertise\Advertise;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function businesses(){
        return $this->hasMany(Business::class);
    }

    public function advertises(){
        return $this->hasMany(Advertise::class);
    }
}
