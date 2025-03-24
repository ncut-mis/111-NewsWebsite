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
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporter $reporter) // 修改參數類型
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporter $reporter)
    {
        
    }
    
}
