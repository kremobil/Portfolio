<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return Inertia::render('Posts/Index', ['posts' => $posts->load('cover')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FileUploadService $fileUploadService)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'coverImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $fileUploadService->uploadFile($data['coverImage'], $data['title']);

        $post = Post::create(
            [
                'title' => $data['title'],
                'body' => stripslashes($data['body']),
                'slug' => Str::slug($data['title']),
                'cover_id' => $image->id,
                'user_id' => auth()->id()
            ]
        );

        return Redirect::route('post.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', ['post' => $post->load('cover')->load('user')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', ['post' => $post->load('cover')->load('user')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, FileUploadService $fileUploadService)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $new_data =  [
            'title' => $data['title'],
            'body' => stripslashes($data['body']),
            'slug' => Str::slug($data['title']),
        ];

        if ($request->hasFile('image')) {
            $file = $fileUploadService->uploadFile($data['image'], $data['title']);
            $new_data['cover_id'] = $file->id;
        }

        $post->update($new_data);

        return Redirect::route('post.show', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return Redirect::route('post.index');
    }

    public function all(): \Inertia\Response
    {
        $posts = Post::all();
        return Inertia::render("AllPosts", ['posts' => $posts->load('cover')]);
    }
}
