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
    public function index()
    {
        $news = News::orderBy('id', 'desc')->get();

        $data = [
            'news' => $news,
        ];

        return view('admin.reporter.index', $data);
    }
   
    public function create()
    {
        $categories = Category::all(); // 確保這裡的變數是正確的
        return view('admin.reporter.create', ['news' => new News(), 'categories' => $categories]);
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

        return redirect()->route('admin.reporter.index');
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
        return view('admin.reporter.edit', ['news' => $news]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news) // 確保這裡的參數是正確的
    {
        $news->update($request->all());
        
        return redirect()->route('admin.reporter.index')->with('success', '更新成功！');
    }

    public function submit(Request $request, News $news) // 確保這裡的參數是正確的
    {
        $news->update($request->all());

        return redirect()->route('admin.reporter.index',['news' => $news]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news) // 確保這裡的參數是正確的
    {
        $news->delete();

        return redirect()->route('admin.reporter.index')->with('success', '刪除成功！');;
    }
}
