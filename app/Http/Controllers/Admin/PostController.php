<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostsRequest;
use App\Models\MediaLibrary;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Show the application posts index.
     */
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::where('status','<>',3)->withCount('comments', 'likes')->with('author')->latest()->paginate(50)
        ]);
    }

    /**
     * Display the specified resource edit form.
     */
    public function edit(Post $post): View
    {
        $user = User::authors()->pluck('name', 'id');
        $status = [
            0 => 'Draft',
            1 => 'Aktif',
            2 => 'Tak Aktif'
        ];
        return view('admin.posts.edit', [
            'post' => $post,
            'users' => $user,
            'status' => $status,
            'media' => MediaLibrary::first()->media()->get()->pluck('name', 'id')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $status = [
            0 => 'Draft',
            1 => 'Aktif',
            2 => 'Tak Aktif'
        ];

        return view('admin.posts.create', [
            'users' => User::authors()->pluck('name', 'id'),
            'status' => $status,
            'media' => MediaLibrary::first()->media()->get()->pluck('name', 'id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostsRequest $request): RedirectResponse
    {
        $post = Post::create($request->only(['title', 'content', 'posted_at', 'author_id', 'thumbnail_id']));

        return redirect()->route('admin.posts.edit', $post)->withSuccess(__('posts.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostsRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->only(['title', 'content', 'posted_at', 'author_id', 'thumbnail_id','status']));

        return redirect()->route('admin.posts.edit', $post)->withSuccess(__('posts.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
//        dd('ff');
        $post->delete();

        return redirect()->route('admin.posts.index')->withSuccess(__('posts.deleted'));
    }
}
