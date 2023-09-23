<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'slug', 'description', 'content', 'parent_id',
        'admin_id', 'image', 'status', 'meta_title', 'meta_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }
}
