<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'description', 'slug', 'status', 'admin_id', 'category_id',
        'method_title', 'method_category', 'thumbnail_image', 'article_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
