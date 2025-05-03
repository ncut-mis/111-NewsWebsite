<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    use HasFactory;

    protected $table = 'reporters';

    protected $fillable = [
        'staff_id', // 確保這裡包含所有需要的欄位
    ];

    // 設定與 Staff 的關聯
    public function staff()
    {
        return $this->belongsTo(Staff::class); // 確保關聯正確
    }
}
