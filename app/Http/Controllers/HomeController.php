<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\News;
use App\Models\ImageTextParagraph;

class HomeController extends Controller
{
    /**
     * 顯示首頁新聞列表，支援分類與即時新聞篩選。
     */
    public function index(Request $request)
    {
        $newsQuery = News::with(['imageParagraph']) // 確保載入圖片段落的關聯
            ->where('status', 2);

        if ($request->category_id === 'live') {
            $newsQuery->where('created_at', '>=', now()->subHours(5));
        } else if ($request->has('category_id')) {
            $newsQuery->where('category_id', $request->category_id);
        }else {
            // ✅ 沒有選分類時，預設為即時新聞
            $newsQuery->where('created_at', '>=', now()->subHours(5));
        }


        $news = $newsQuery->get();
        $categories = Category::all();

        return view('home.index', compact('news', 'categories'));
    }

    /**
     * 顯示特定新聞的詳細內容與相關段落。
     */
    public function show($id)
    {
        $newsItem = News::findOrFail($id); // 使用 findOrFail() 會在找不到新聞時拋出 404 錯誤
        $relatedParagraphs = ImageTextParagraph::where('news_id', $newsItem->id)
            ->orderBy('order')
            ->get();

        return view('show.new', compact('newsItem', 'relatedParagraphs')); // 建立一個新的視圖 show/new.blade.php
    }
}
