<?php

namespace App\Http\Controllers;
use App\Events\UserSubscribed;
use App\Mail\WelcomeMail;
use App\Models\Post;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct()
    {
        // Применить промежуточное ПО 'auth' ко всем методам, кроме 'index' и 'show'
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $posts=Post::latest()->paginate(6);

        return view('posts.index', ['posts'=>$posts]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        
        //Validate
        $fields=$request->validate([
            'title'=>['required','max:255'],
            'body'=>['required']
        ]);
        //Crete a post
        Auth::user()->posts()->create($fields);
        return back()->with('success','Your post was created');
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post'=>$post]);
    }

    public function edit(Post $post)
    {
        Gate::authorize('modify',$post);
        return view('posts.edit', ['post'=>$post]);
    }

    public function update(Request $request, Post $post)
    {
        //Authorizing the action
        Gate::authorize('modify',$post);

        //Validate
        $fields=$request->validate([
            'title'=>['required','max:255'],
            'body'=>['required']
        ]);
        //Update a post
        $post->update($fields);

        return redirect()->route('dashboard')->with('success','Your post was updated');
    }

    public function destroy(Post $post)
    {
        //Authorizing the action
        Gate::authorize('modify',$post);

        //Delete the post
        $post->delete();

        //Redirect back to dashboard
        return back()->with('delete','Your post was deleted');
    }
}
