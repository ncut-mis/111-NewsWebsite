<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class friendly_versions extends Model
{
    protected $fillable = ['news_id', 'category', 'content'];

    // 設定與 News 的關聯
    public function news()
    {
        return $this->belongsTo(news::class);
    }
}
