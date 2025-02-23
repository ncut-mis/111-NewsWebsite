<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favorites extends Model
{
    protected $fillable = ['user_id', 'news_id'];

    // 設定與 User 的關聯
    public function user()
    {
        return $this->belongsTo(user::class);
    }

    // 設定與 News 的關聯
    public function news()
    {
        return $this->belongsTo(news::class);
    }
}
