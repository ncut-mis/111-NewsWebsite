<?php

namespace App\Http\Controllers;

use App\Models\favorites;
use App\Http\Requests\StorefavoritesRequest;
use App\Http\Requests\UpdatefavoritesRequest;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorefavoritesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(favorites $favorites)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(favorites $favorites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefavoritesRequest $request, favorites $favorites)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(favorites $favorites)
    {
        //
    }
}
