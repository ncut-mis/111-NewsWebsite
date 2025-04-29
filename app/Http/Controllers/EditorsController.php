<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use App\Models\News;
use App\Http\Requests\StoreeditorsRequest;
use App\Http\Requests\UpdateeditorsRequest;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Category;
use App\Models\ImageTextParagraph;

class EditorsController extends Controller
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

        return view('staff.editor.index', ['news' => $news]);
    }

    /**
     * 顯示新增編輯的表單。
     */
    public function create()
    {
        //
    }

    /**
     * 儲存新的編輯。
     */
    public function store(StoreeditorsRequest $request)
    {
        //
    }

    /**
     * 顯示特定編輯的詳細內容。
     */
    public function show(editors $editors)
    {
        //
    }

    /**
     * 顯示編輯的表單。
     */
    public function edit(editors $editors)
    {
        //
    }

    /**
     * 更新特定編輯的內容。
     */
    public function update(UpdateeditorsRequest $request, editors $editors)
    {
        //
    }

    /**
     * 刪除特定編輯。
     */
    public function destroy(editors $editors)
    {
        //
    }

    /**
     * 顯示待審核的新聞列表。
     */
    public function review()
    {
        $news = News::where('status', 1)->get();
        return view('staff.editor.review', ['news' => $news]);
    }

    /**
     * 顯示已發布的新聞列表。
     */
    public function published()
    {
        $news = News::where('status', 2)->get();
        return view('staff.editor.published', ['news' => $news]);
    }

    /**
     * 顯示被退回的新聞列表。
     */
    public function return1()
    {
        $news = News::where('status', 3)->get();
        return view('staff.editor.return', ['news' => $news]);
    }

    /**
     * 顯示已移除的新聞列表。
     */
    public function removed()
    {
        $news = News::where('status', 4)->get();
        return view('staff.editor.removed', ['news' => $news]);
    }

    /**
     * 將新聞重新發布。
     */
    public function republish($id)
    {
        $news = News::findOrFail($id);
        $news->status = 2; // 將狀態設置為已發布
        $news->save();

        return redirect()->route('staff.editor.published')->with('success', '新聞已重新上架');
    }

    /**
     * 將新聞下架。
     */
    public function unpublish($id)
    {
        $news = News::findOrFail($id);
        $news->status = 4; // 將狀態設置為已下架
        $news->save();

        return redirect()->route('staff.editor.removed')->with('success', '新聞已下架');
    }

    /**
     * 將新聞退回給記者。
     */
    public function return($id)
    {
        $news = News::findOrFail($id);
        $news->status = 3; // 將狀態設置為退回
        $news->save();

        return redirect()->route('staff.editor.review')->with('success', '新聞已退回');
    }
    public function check($id)
    {

        $newsItem = News::findOrFail($id); // 或是你要看的資料
        $categories = Category::all();
        $relatedParagraphs = ImageTextParagraph::where('news_id', $newsItem->id)
            ->orderBy('order')
            ->get();
        return view('staff.editor.editornews', compact('newsItem','categories', 'relatedParagraphs'));
    }
}
