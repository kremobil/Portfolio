<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request, FileUploadService $fileUploadService) {
        $requestData = $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = $fileUploadService->uploadFile($requestData['upload']);

        return [
            "url" => url($image->path),
        ];
    }
}
