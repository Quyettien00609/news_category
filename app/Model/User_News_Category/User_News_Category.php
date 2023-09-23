<?php

namespace App\Model\User_News_Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_News_Category extends Model
{
    use HasFactory;
    protected $table = 'user_news_category'; // Tên bảng

    protected $fillable = [
        'user_id', 'news_id', 'category_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
