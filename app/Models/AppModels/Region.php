<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertise\Advertise;

class Region extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function advertises(){
        return $this->hasMany(Advertise::class);
    }
}
