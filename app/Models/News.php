<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['category_id', 'reporter_id', 'editor_id', 'title', 'status', 'web_version', 'word_version'];

    protected $casts = [
        // 在這裡添加您需要的類型轉換
        'category_id' => 'integer',
        'reporter_id' => 'integer',
        'editor_id' => 'integer',
        'status' => 'integer', // 確保 status 為整數類型
        'web_version' => 'string',
        'word_version' => 'string',
    ];

    // 設定與 Category 的關聯
    public function category()
    {
        return $this->belongsTo(category::class);
    }

    // 設定與 Reporter 的關聯
    public function reporter()
    {
        return $this->belongsTo(reporter::class);
    }

    // 設定與 Editor 的關聯
    public function editor()
    {
        return $this->belongsTo(editor::class);
    }

    public function favorites()
    {
        return $this->hasMany(favorite::class);
    }

    public function imageTextParagraphs()
    {
        return $this->hasMany(ImageTextParagraph::class)->ordered();
    }
}
