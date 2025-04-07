<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
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
   
    public function create()
    {
        $categories = Category::all(); // 確保這裡的變數是正確的
        return view('staff.reporter.create', ['news' => new News(), 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(News $news) // 確保這裡的參數是正確的
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news) // 確保這裡的參數是正確的
    {
        return view('staff.reporter.edit', ['news' => $news]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news) // 確保這裡的參數是正確的
    {
        $news->update($request->all());
        
        return redirect()->route('staff.reporter.news.index')->with('success', '更新成功！');
    }

    public function submit(Request $request, News $news) // 確保這裡的參數是正確的
    {
        $news->update($request->all());

        return redirect()->route('staff.reporter.news.index',['news' => $news]);
    }

    public function approve(News $news)
    {
        $news->update(['status' => 2]); // 將狀態更新為 2，表示已審核
        return redirect()->route('staff.editor.dashboard')->with('success', '新聞已審核！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news) // 確保這裡的參數是正確的
    {
        $news->delete();

        return redirect()->route('staff.reporter.news.index')->with('success', '刪除成功！');;
    }

    public function writing()
    {
        $news = News::where('status', 0)->get();
        return view('staff.reporter.writing', ['news' => $news]);
    }

    public function review()
    {
        $news = News::where('status', 1)->get();
        return view('staff.reporter.review', ['news' => $news]);
    }

    public function published()
    {
        $news = News::where('status', 2)->get();
        return view('staff.reporter.published', ['news' => $news]);
    }

    public function return()
    {
        $news = News::where('status', 3)->get();
        return view('staff.reporter.return', ['news' => $news]);
    }

    public function removed()
    {
        $news = News::where('status', 4)->get();
        return view('staff.reporter.removed', ['news' => $news]);
    }
}
