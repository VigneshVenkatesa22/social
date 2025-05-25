<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth; // Add this at the top
class ArticleController extends Controller
{
public function create()
{
    $tags = Tag::all(); // Fetch from DB
    return view('create', compact('tags'));
}

   public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'tag_id'  => 'required|exists:tags,id',
        'image'   => 'nullable|image|max:2048',
        
    ]);

    try {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        Article::create([
            'title'   => $request->title,
            'content' => $request->content,
            'tag_id'  => $request->tag_id,
            'image'   => $imagePath,
            'user_id' => Auth::id()
        ]);
        

        return redirect()->back()->with('success', 'Article created successfully!');
    } catch (\Exception $e) {
        dd($e);
        return redirect()->back()->with('error', 'Something went wrong!');
    }

}
    public function index()
    {
        // Fetch all articles with tag and user info
        $articles = Article::with(['tag', 'user'])->latest()->get();
        return view('home', compact('articles'));
    }
}