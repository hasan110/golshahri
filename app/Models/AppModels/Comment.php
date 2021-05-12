<?php

namespace App\Models\AppModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }
}
