<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleLikeController extends Controller
{
        public function toggleLike(Article $article)
    {
        $user = auth()->user();
        
        if ($article->isLikedBy($user)) {
            $article->likes()->detach($user);
            $liked = false;
        } else {
            $article->likes()->attach($user);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $article->likes()->count(),
            'user'=>$user
        ]);
    }
}
