<?php

namespace App\Http\Controllers;

use App\Models\Editors; // 確保正確引入模型
use App\Models\News;
use App\Http\Requests\StoreeditorsRequest;
use App\Http\Requests\UpdateeditorsRequest;
use Illuminate\Http\Request; // 確保正確引入 Request 類

class EditorsController extends Controller
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

        return view('staff.editor.index', ['news' => $news]);
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
    public function store(StoreeditorsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(editors $editors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(editors $editors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateeditorsRequest $request, editors $editors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(editors $editors)
    {
        //
    }
    public function review()
    {
        $news = News::where('status', 1)->get();
        return view('staff.editor.review', ['news' => $news]);
    }

    public function published()
    {
        $news = News::where('status', 2)->get();
        return view('staff.editor.published', ['news' => $news]);
    }

    public function return1()
    {
        $news = News::where('status', 3)->get();
        return view('staff.editor.return', ['news' => $news]);
    }

    public function removed()
    {
        $news = News::where('status', 4)->get();
        return view('staff.editor.removed', ['news' => $news]);
    }

    public function republish($id)
    {
        $news = News::findOrFail($id);
        $news->status = 2; // 將狀態設置為已發布
        $news->save();

        return redirect()->route('staff.editor.published')->with('success', '新聞已重新上架');
    }

    public function unpublish($id)
    {
        $news = News::findOrFail($id);
        $news->status = 4; // 將狀態設置為已下架
        $news->save();

        return redirect()->route('staff.editor.removed')->with('success', '新聞已下架');
    }

    public function return($id)
    {
        $news = News::findOrFail($id);
        $news->status = 3; // 將狀態設置為退回
        $news->save();

        return redirect()->route('staff.editor.review')->with('success', '新聞已退回');
    }
    
}
