<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Vote extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function votable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
