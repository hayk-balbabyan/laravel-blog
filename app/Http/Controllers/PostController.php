<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'desc'); // По умолчанию сортируем по убыванию
        $order = $request->query('order', 'title');

        $posts = Post::orderBy($order, $sort)->get();

        return view('dashboard', compact('posts', 'sort'));
    }

    public function myPosts()
    {
     $user = User::where('id', 1)->with('posts')->get();
        $posts = Post::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('my_post', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/posts', $filename);
            $validated['image'] = 'posts/' . $filename;
        }

        $validated['user_id'] = auth()->id();

        Post::create($validated);

        return redirect()->route('my_post')->with('success', 'Пост успешно создан!');
    }


    public function edit(Post $post)
    {
        if (auth()->user()->id !== $post->user_id ){
            abort(401);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image && Storage::exists('public/' . $post->image)) {
                Storage::delete('public/' . $post->image);
            }

            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/posts', $filename);

            $validated['image'] = 'posts/' . $filename;
        }

        $post->update($validated);
        return redirect()->route('my_post')->with('success', 'Пост успешно обновлен!');

    }



    public function destroy(Post $post)
    {
        if(auth()->id() !== $post->user_id){
            abort(401);
        }
        $post->delete();
        return redirect()->back();
    }
}
