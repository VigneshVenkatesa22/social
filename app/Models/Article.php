<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'tag_id',
        'image',
        'user_id'
    ];
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
public function likes()
{
    return $this->belongsToMany(User::class, 'article_likes')->withTimestamps();
}

public function isLikedBy($user)
{
    return $this->likes()->where('user_id', $user->id)->exists();
}
public function comments()
{
    return $this->hasMany(Comment::class);
}

}
