<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\ImageTextParagraph;
use App\Models\Staff;

class NewsController extends Controller
{
    /**
     * 顯示新聞列表，支援根據狀態篩選。
     */
    public function index(Request $request)
    {
        $status = $request->query('status', null);
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $query = News::orderBy('id', 'desc')
                ->where('reporter_id', $reporter->id);
            if (!is_null($status)) {
                $query->where('status', $status);
            }
            $news = $query->get();
        }
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
            $data['category_id'] = $firstCategory ? $firstCategory->id : null;
        }
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        if ($reporter) {
            $data['reporter_id'] = $reporter->id;
        }
        if (!$request->has('editor_id')) {
            $data['editor_id'] = 1; // 或根據你的邏輯
        }
        News::create($data);
        return redirect()->route('staff.reporter.news.index');
    }

    /**
     * 顯示特定新聞的詳細內容。
     */
    public function show($id)
    {
        $newsItem = News::findOrFail($id);
        $categories = Category::all();
        $relatedParagraphs = ImageTextParagraph::where('news_id', $newsItem->id)
            ->orderBy('order')
            ->get();
        return view('staff.reporter.show', compact('newsItem', 'categories', 'relatedParagraphs'));

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
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $news = News::where('status', 0)
                ->where('reporter_id', $reporter->id)
                ->get();
        }
        return view('staff.reporter.writing', ['news' => $news]);
    }

    /**
     * 顯示所有待審核的新聞。
     */
    public function review()
    {
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $news = News::where('status', 1)
                ->where('reporter_id', $reporter->id)
                ->get();
        }
        return view('staff.reporter.review', ['news' => $news]);
    }

    /**
     * 顯示所有已發布的新聞。
     */
    public function published()
    {
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $news = News::where('status', 2)
                ->where('reporter_id', $reporter->id)
                ->get();
        }
        return view('staff.reporter.published', ['news' => $news]);
    }

    /**
     * 顯示所有被退回的新聞。
     */
    public function return()
    {
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $news = News::where('status', 3)
                ->where('reporter_id', $reporter->id)
                ->get();
        }
        return view('staff.reporter.return', ['news' => $news]);
    }

    /**
     * 顯示所有已移除的新聞。
     */
    public function removed()
    {
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $news = collect();
        if ($reporter) {
            $news = News::where('status', 4)
                ->where('reporter_id', $reporter->id)
                ->get();
        }
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
        $staff = auth('staff')->user();
        $reporter = \App\Models\Reporter::where('staff_id', $staff->id)->first();
        $reporterId = $reporter ? $reporter->id : null;
        News::create([
            'title' => $validated['title'],
            'category_id' => $validated['category'],
            'reporter_id' => $reporterId,
            'editor_id' => 1,
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
