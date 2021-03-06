<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relations
    public function books()
    {
        return $this->belongsToMany(Book::class)->latest();
    }

    // Scopes
    public function scopeRetrieveCategoriesId($query, $categories)
    {
        foreach ($categories as $category) {
            $this->firstOrCreate(['name' => $category]);
        }
        return $query->whereIn('name', $categories)->get()->pluck('id');
    }
}
