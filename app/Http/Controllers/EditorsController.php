<?php

namespace App\Http\Controllers;

use App\Models\Editors; // 確保正確引入模型
use App\Models\News;
use App\Http\Requests\StoreeditorsRequest;
use App\Http\Requests\UpdateeditorsRequest;

class EditorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::where('status', 1)->get(); // 從資料庫中抓取狀態為 1 的新聞
        return view('staff.editor.index', compact('news')); // 使用 compact 傳遞 $news 到視圖
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
    
}
