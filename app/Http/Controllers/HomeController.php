<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $news = \App\Models\News::all(); // 從資料庫中取得所有新聞
        return view('home.index', compact('news')); // 傳遞 $news 到視圖
    }
}