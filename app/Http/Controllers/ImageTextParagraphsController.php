<?php

namespace App\Http\Controllers;

use App\Models\image_text_paragraphs;
use App\Models\ImageTextParagraph;
use App\Models\News;
use App\Http\Requests\Storeimage_text_paragraphsRequest;
use App\Http\Requests\Updateimage_text_paragraphsRequest;

class ImageTextParagraphsController extends Controller
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
    public function store(Storeimage_text_paragraphsRequest $request)
    {
        $contents = $request->input('contents', []);
        foreach ($contents as $content) {
            ImageTextParagraph::create([
                'news_id' => $request->news_id,
                'category' => $content['category'],
                'content' => $content['content'],
                'order' => $content['order'],
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(image_text_paragraphs $image_text_paragraphs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $news = News::with('imageTextParagraphs')->findOrFail($id);
        return view('staff.reporter.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateimage_text_paragraphsRequest $request, image_text_paragraphs $image_text_paragraphs)
    {
        $contents = $request->input('contents', []);
        foreach ($contents as $content) {
            ImageTextParagraph::updateOrCreate(
                ['news_id' => $request->news_id, 'order' => $content['order']],
                [
                    'category' => $content['category'],
                    'content' => $content['content'],
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(image_text_paragraphs $image_text_paragraphs)
    {
        //
    }
}
