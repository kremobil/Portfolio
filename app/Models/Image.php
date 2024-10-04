<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'path',
        'alt',
        'author'
    ];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute() {
        return url($this->attributes['path']);
    }

    use HasFactory;
}
