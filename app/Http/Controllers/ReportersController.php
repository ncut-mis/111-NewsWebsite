<?php

namespace App\Http\Controllers;

use App\Models\Reporter;
use App\Http\Requests\StorereportersRequest;
use App\Http\Requests\UpdatereportersRequest;
use Illuminate\Http\Request; // 添加這行

class ReportersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reporters = Reporter::orderBy('id', 'desc')->get();

        return view('admin.reporter.index', compact('reporters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reporter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Reporter::create($request->all());

        return redirect()->route('admin.reporter.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reporter $reporter) // 修改參數類型
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporter $reporter)
    {
        $data = [
            'reporter' => $reporter,
        ];

        return view('admin.reporter.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporter $reporter) // 修改參數類型
    {
        $reporter->update($request->all());

        return redirect()->route('admin.reporter.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporter $reporter)
    {
        $reporter->delete();

        return redirect()->route('admin.reporter.index');
    }
    
}
