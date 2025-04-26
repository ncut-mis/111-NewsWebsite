<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\News;
use App\Models\ImageTextParagraph;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $newsQuery = \App\Models\News::with(['imageParagraph'])
            ->where('status', 2); // ✅ 加上這行只撈 status = 2 的新聞

        if ($request->category_id === 'live') {
            // 如果是「即時」，取 5 小時內的新聞
            $newsQuery->where('created_at', '>=', now()->subHours(5));
        } else if ($request->has('category_id')) {
            $newsQuery->where('category_id', $request->category_id);
        }else {
            // ✅ 沒有選分類時，預設為即時新聞
            $newsQuery->where('created_at', '>=', now()->subHours(5));
        }


        $news = $newsQuery->get();
        $categories = \App\Models\Category::all();

        return view('home.index', compact('news', 'categories'));
    }
    public function show($id)
    {
        $newsItem = News::findOrFail($id); // 使用 findOrFail() 會在找不到新聞時拋出 404 錯誤
        $relatedParagraphs = ImageTextParagraph::where('news_id', $newsItem->id)
            ->orderBy('order')

            ->get();
        return view('show.new', compact('newsItem', 'relatedParagraphs')); // 建立一個新的視圖 show/new.blade.php
    }

}
