<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTextParagraph extends Model
{
    use HasFactory;

    // 定義類別常數
    const CATEGORY_TEXT = 0; // 文字
    const CATEGORY_IMAGE = 1; // 圖片
    const CATEGORY_VIDEO = 2; // 影片

    protected $fillable = ['news_id', 'category', 'title', 'content', 'order', 'image_path'];
    public $timestamps = false;
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
