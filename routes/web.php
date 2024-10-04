<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    Route::get('/dashboard/posts', [PostController::class, 'index'])->name('post.index');
    Route::get('/dashboard/posts/{post}', [PostController::class, 'show'])->name('post.show');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/dashboard/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/dashboard/{post}', [PostController::class, 'update'])->name('post.update');
    Route::get('/posts', [PostController::class, 'all'])->name('post.all');
});

Route::post('/images/store', [ImageController::class, 'store'])->name('image.store');

Route::get('/homepage', function () {
    return Inertia::render('HomePage');
})->name('homepage');



require __DIR__.'/auth.php';
