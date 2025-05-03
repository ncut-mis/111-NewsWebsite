<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\users;
use App\Http\Requests\StoreusersRequest;
use App\Http\Requests\UpdateusersRequest;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home.index');
        // 只搜尋已通過的新聞（status = 2）
        $news = News::where('status', 2)
            ->where('title', 'like', '%' . $query . '%')
            ->with('reporter', 'imageParagraph')
            ->get();

        return view('view.home.search', compact('news', 'query'));
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
    public function store(StoreusersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(users $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateusersRequest $request, users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(users $users)
    {
        //
    }

}
