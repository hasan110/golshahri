<?php

namespace App\Models\Advertise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertise\Advertise;

class AdvertisePicture extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function advertise(){
        return $this->hasMany(Advertise::class);
    }
}
