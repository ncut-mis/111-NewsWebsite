<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    use HasFactory;

    protected $table = 'reporters';

    protected $fillable = [
        'staff_id',
    ];

    // 設定與 Staff 的關聯
    public function staff()
    {
        return $this->belongsTo(Staff::class); // 更新關聯為 Staff
    }
}
