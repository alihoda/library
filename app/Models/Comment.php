<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'book_id',
        'user_id',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeLatest($query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeReviewerComments($query)
    {
        dd($query->where('user->reviewer', true));
        return $query->where($query->user->reviewer, true);
    }
}
