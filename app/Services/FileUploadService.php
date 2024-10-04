<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function uploadFile($file, $alt = null, $author = null) {
        $fileName = $file->getClientOriginalName();

        $iterator = 1;
        while (Image::query()->where('name', $fileName)->exists()){
           $fileName = $file->getClientOriginalExtension() . $iterator;
           $iterator++;
        }

        Storage::disk('public')->putFileAs('uploads', $file, Str::replace(' ', '_', $fileName));

        $image = Image::create(
            [
                "name" => $fileName,
                "path" => 'uploads/' . Str::replace(' ', '_', $fileName),
                "alt" => $alt,
                "author" => $author,
            ]
        );

        return $image;
    }
}
