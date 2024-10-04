<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $fillable = [
        "title",
        "body",
        "cover_id",
        "slug",
        "user_id",
    ];

    public function cover(): HasOne {
        return $this->hasOne(Image::class, 'id', 'cover_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    use HasFactory;
}
