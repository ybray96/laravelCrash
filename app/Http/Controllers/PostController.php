<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class PostController extends Controller
{
    public function __construct()
    {
        // Применить промежуточное ПО 'auth' ко всем методам, кроме 'index' и 'show'
        $this->middleware(['auth','verified'], ['except' => ['index', 'show']]);
    }

    public function index()
    {

        $posts = Post::latest()->paginate(6);

        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {


        //Validate
        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:3000', 'mimes:png,jpg,webp']
        ]);

        //Store image if exists
        $path = null;
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('posts_images', $request->image);
        }


        //Crete a post
        $post = Auth::user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path,
        ]);

        //Send mail
        Mail::to(Auth::user())->send(new WelcomeMail(Auth::user(), $post));

        return back()->with('success', 'Your post was created');
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    public function edit(Post $post)
    {
        Gate::authorize('modify', $post);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        //Authorizing the action
        Gate::authorize('modify', $post);

        //Validate
        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:3000', 'mimes:png,jpg,webp']
        ]);
        //Store image if exists
        $path = $post->image ?? null;
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = Storage::disk('public')->put('posts_images', $request->image);
        }

        //Update a post
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path
        ]);

        return redirect()->route('dashboard')->with('success', 'Your post was updated');
    }

    public function destroy(Post $post)
    {
        //Authorizing the action
        Gate::authorize('modify', $post);

        //Delete post image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        //Delete the post
        $post->delete();

        //Redirect back to dashboard
        return back()->with('delete', 'Your post was deleted');
    }
}
