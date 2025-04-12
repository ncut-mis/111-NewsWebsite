<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name']; // 允許批量填充的欄位
    public function news()
    {
        return $this->hasMany(News::class);
    }
}

