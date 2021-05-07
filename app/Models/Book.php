<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'user_id',
        'publisher_id'
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
}
