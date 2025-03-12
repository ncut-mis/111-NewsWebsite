<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    protected $fillable = ['admin_id'];

    // 設定與 Admin 的關聯
    public function admin()
    {
        return $this->belongsTo(admin::class);
    }
}
