<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Http\Requests\StorefavoritesRequest;
use App\Http\Requests\UpdatefavoritesRequest;
use Illuminate\Http\Request;
use App\Models\Category;

class FavoritesController extends Controller
{
    /**
     * 顯示收藏列表。
     */
    public function index()
    {
        //
    }

    /**
     * 顯示新增收藏的表單。
     */
    public function create()
    {
        //
    }

    /**
     * 儲存新的收藏。
     */
    public function store(StorefavoritesRequest $request)
    {
        //
    }

    /**
     * 顯示特定收藏的詳細內容。
     */
    public function show(favorites $favorites)
    {
        //
    }

    /**
     * 顯示編輯收藏的表單。
     */
    public function edit(favorites $favorites)
    {
        //
    }

    /**
     * 更新特定收藏的內容。
     */
    public function update(UpdatefavoritesRequest $request, favorites $favorites)
    {
        //
    }

    /**
     * 刪除特定收藏。
     */
    public function destroy(favorites $favorites)
    {
        //
    }

    /**
     * 新增新聞至收藏列表。
     */
    public function addFavorite(Request $request)
    {
        // 取得當前用戶的 ID
        $userId = auth()->id();  // 或者直接用 `Auth::id()` 也可以

        // 確保用戶已經登入
        if (!$userId) {
            return redirect()->route('login')->with('error', '請先登入');
        }

        // 取得要收藏的新聞 ID
        $newsId = $request->input('news_id');

        // 檢查該收藏是否已經存在，避免重複收藏
        $existingFavorite = Favorite::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        if ($existingFavorite) {
            return back()->with('error', '你已經收藏過這篇新聞');
        }

        // 新增收藏
        Favorite::create([
            'news_id' => $newsId,
            'user_id' => $userId,
        ]);

        return back()->with('success', '成功加入收藏');
    }

    /**
     * 顯示用戶的收藏列表。
     */
    public function favoriteList()
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', '請先登入');
        }

        $favorites = Favorite::where('user_id', $userId)
            ->with(['news.imageParagraph']) // ⚠️ 預加載圖片關聯
            ->get();

        $categories = Category::all(); // 🟢 把分類撈出來

        return view('favorites', compact('favorites', 'categories')); // 傳到 Blade
    }

    /**
     * 從收藏列表中移除新聞。
     */
    public function removeFavorite(Request $request)
    {
        // 取得當前用戶的 ID
        $userId = auth()->id();

        // 確保用戶已經登入
        if (!$userId) {
            return redirect()->route('login')->with('error', '請先登入');
        }

        // 取得要取消收藏的新聞 ID
        $newsId = $request->input('news_id');

        // 檢查該收藏是否存在
        $favorite = Favorite::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        if ($favorite) {
            // 刪除該收藏
            $favorite->delete();
            return back()->with('success', '成功取消收藏');
        }

        return back()->with('error', '該收藏不存在');
    }
}
