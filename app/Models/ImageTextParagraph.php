<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTextParagraph extends Model
{
    protected $fillable = ['news_id', 'category', 'title', 'content', 'order'];

    // 設定與 News 的關聯
    public function news()
    {
        return $this->belongsTo(news::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
