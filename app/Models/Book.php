<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'user_id',
        'publisher_id',
        'pdf_path'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // Scopes
    public function scopeLatest($query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function url()
    {
        return Storage::url($this->pdf_path);
    }
}
