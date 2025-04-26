<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Http\Requests\StorecategoriesRequest;
use App\Http\Requests\UpdatecategoriesRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * 顯示所有分類。
     */
    public function index()
    {


        $categories = Category::all();
        return view('staff.editor.Category.index', compact('categories'));
    }

    /**
     * 顯示新增分類的表單。
     */
    public function create()
    {
        return view('staff.editor.Category.create');
    }

    /**
     * 儲存新的分類。
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->only('name'));
        return redirect()->route('staff.editor.categories.index')->with('success', '類別新增成功');
    }

    /**
     * 顯示特定分類的詳細內容。
     */
    public function show(categories $categories)
    {
        //
    }

    /**
     * 顯示編輯分類的表單。
     */
    public function edit(Category $category)
    {
        return view('staff.editor.Category.edit', compact('category'));
    }

    /**
     * 更新特定分類的內容。
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->only('name'));
        return redirect()->route('staff.editor.categories.index')->with('success', '類別更新成功');
    }

    /**
     * 刪除特定分類。
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('staff.editor.categories.index')->with('success', '類別刪除成功');
    }

    /**
     * 顯示分類與對應的新聞列表，支援篩選。
     */
    public function dashboard(Request $request)
    {
        $categories = Category::all();
        $news = collect(); // 空集合，預設為空

        if ($request->has('category_id')) {
            // 篩選出符合 category_id 且 status = 2 的新聞
            $news = \App\Models\News::where('category_id', $request->category_id)
                ->where('status', 2) // 加入 status = 2 的條件
                ->get();
        }

        return view('dashboard', compact('categories', 'news'));
    }
}
