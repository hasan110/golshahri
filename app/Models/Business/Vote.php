<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business\Business;

class Vote extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function vote(){
        return $this->belongsTo(Business::class);
    }
}
