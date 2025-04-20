<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\favorites;
use App\Http\Requests\StorefavoritesRequest;
use App\Http\Requests\UpdatefavoritesRequest;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorefavoritesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(favorites $favorites)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(favorites $favorites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefavoritesRequest $request, favorites $favorites)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(favorites $favorites)
    {
        //
    }
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
    public function favoriteList()
    {
        // 確保用戶已經登入
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', '請先登入');
        }

        // 取得用戶收藏的所有新聞
        $favorites = Favorite::where('user_id', $userId)
            ->with('news')  // 預加載新聞資料
            ->get();

        return view('favorites', compact('favorites'));
    }
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
