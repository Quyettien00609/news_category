<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'reader_id',
        'news_id',
        'content',
    ];

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
