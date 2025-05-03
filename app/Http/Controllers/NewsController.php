<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
use App\Models\Favorite;

class NewsController extends Controller
{
    /**
     * 顯示新聞列表，支援根據狀態篩選。
     */
    public function index(Request $request)
    {
        $status = $request->query('status', null); // 獲取狀態參數
        $query = News::orderBy('id', 'desc');

        if (!is_null($status)) {
            $query->where('status', $status); // 根據狀態篩選
        }

        $news = $query->get();

        $data = [
            'news' => $news,
        ];

        return view('staff.reporter.index', $data);
    }

    /**
     * 顯示新增新聞的表單，並載入所有分類。
     */
    public function create()
    {
        $categories = Category::all(); // 確保這裡的變數是正確的
        return view('staff.reporter.create', ['news' => new News(), 'categories' => $categories]);
    }

    /**
     * 儲存新新聞，並設置預設的分類、記者與編輯。
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (!$request->has('category_id')) {
            $firstCategory = Category::first();
            $data['category_id'] = $firstCategory ? $firstCategory->id : null; // 設置默認的 category_id
        }
        if (!$request->has('reporter_id')) {
            $data['reporter_id'] = auth()->user()->id ?? 1; // 設置默認的 reporter_id
        }
        if (!$request->has('editor_id')) {
            $data['editor_id'] = auth()->user()->id ?? 1; // 設置默認的 editor_id
        }
        News::create($data);

        return redirect()->route('staff.reporter.news.index');
    }

    /**
     * 顯示特定新聞的詳細內容。
     */
    public function show(News $news)
    {
        //
    }

    /**
     * 顯示編輯新聞的表單，並載入所有分類。
     */
    public function edit(News $news)
    {
        $categories = Category::all(); // 取得所有類別
        return view('staff.reporter.edit', ['news' => $news, 'categories' => $categories]);
    }

    /**
     * 更新特定新聞的標題與分類。
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer|exists:categories,id',
        ]);

        $news->update([
            'title' => $validated['title'],
            'category_id' => $validated['category'],
        ]);

        return redirect()->route('staff.reporter.news.writing')->with('success', '更新成功！');
    }

    /**
     * 審核新聞，將狀態更新為已審核。
     */
    public function approve(News $news)
    {
        $news->update(['status' => 2]); // 將狀態更新為 2，表示已審核
        return redirect()->route('staff.editor.dashboard')->with('success', '新聞已審核！');
    }

    /**
     * 刪除特定新聞。
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('staff.reporter.news.writing')->with('success', '刪除成功！');;
    }

    /**
     * 顯示所有撰寫中的新聞。
     */
    public function writing()
    {
        $news = News::where('status', 0)->get();
        return view('staff.reporter.writing', ['news' => $news]);
    }

    /**
     * 顯示所有待審核的新聞。
     */
    public function review()
    {
        $news = News::where('status', 1)->get();
        return view('staff.reporter.review', ['news' => $news]);
    }

    /**
     * 顯示所有已發布的新聞。
     */
    public function published()
    {
        $news = News::where('status', 2)->get();
        return view('staff.reporter.published', ['news' => $news]);
    }

    /**
     * 顯示所有被退回的新聞。
     */
    public function return()
    {
        $news = News::where('status', 3)->get();
        return view('staff.reporter.return', ['news' => $news]);
    }

    /**
     * 顯示所有已移除的新聞。
     */
    public function removed()
    {
        $news = News::where('status', 4)->get();
        return view('staff.reporter.removed', ['news' => $news]);
    }

    /**
     * 儲存新聞的標題與分類，並設置預設值。
     */
    public function saveTitleCategory(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer|exists:categories,id',
        ]);

        News::create([
            'title' => $validated['title'],
            'category_id' => $validated['category'],
            'reporter_id' => 1, // 預設為 1
            'editor_id' => 1, // 預設為 1
            'status' => 0,
        ]);

        return redirect()->route('staff.reporter.news.writing')->with('success', '標題與類別已儲存！');
    }

    /**
     * 提交新聞以供審核，將狀態更新為待審核。
     */
    public function submit($id)
    {
        $news = News::findOrFail($id);
        $news->status = 1;
        $news->save();

        return redirect()->route('staff.reporter.news.review')->with('success', '新聞已提交');
    }

    /**
     * 查詢新聞，僅根據標題進行模糊搜尋。
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $news = News::where('title', 'like', '%' . $query . '%')->get(); // 僅查詢 title

        return view('staff.reporter.news.search', compact('news', 'query')); // 傳遞查詢結果與關鍵字
    }
}
