<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    protected $fillable = ['category_id', 'reporter_id', 'editor_id', 'title', 'status', 'web_version', 'word_version'];

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
        return $this->hasMany(image_text_paragraph::class);
    }
}
