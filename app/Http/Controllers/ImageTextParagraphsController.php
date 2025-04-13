<?php

namespace App\Http\Controllers;

use App\Models\ImageTextParagraph;
use App\Models\News;
use App\Http\Requests\Storeimage_text_paragraphsRequest;
use App\Http\Requests\Updateimage_text_paragraphsRequest;
use Illuminate\Http\Request;

class ImageTextParagraphsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($newsId)
    {
        $news = News::with('imageTextParagraphs')->findOrFail($newsId);
        return view('staff.reporter.content', compact('news'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_id' => 'required|integer|exists:news,id',
            'category' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'required|string',
            'order' => 'required|integer',
        ]);

        try {
            // 新增新的段落
            $paragraph = ImageTextParagraph::create($validated);

            return response()->json(['success' => true, 'id' => $paragraph->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|string',
        ]);

        try {
            $paragraph = ImageTextParagraph::findOrFail($id);
            $paragraph->update($validated);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paragraph = ImageTextParagraph::find($id);
        if ($paragraph) {
            $paragraph->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => '內容不存在'], 404);
    }

    /**
     * Save content.
     */
    public function saveContent(Request $request)
    {
        $validated = $request->validate([
            'news_id' => 'required|integer|exists:news,id',
            'contents' => 'required|array',
            'contents.*.category' => 'required|integer',
            'contents.*.content' => 'required|string',
            'contents.*.order' => 'required|integer',
        ]);

        // 按照順序新增內容
        foreach ($validated['contents'] as $content) {
            ImageTextParagraph::create([
                'news_id' => $validated['news_id'],
                'category' => $content['category'],
                'content' => $content['content'],
                'order' => $content['order'],
            ]);
        }

        return redirect()->route('staff.reporter.news.index')->with('success', '內容已儲存！');
    }

    /**
     * Update order of paragraphs.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'nullable|integer|exists:image_text_paragraphs,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $orderData) {
            if ($orderData['id']) {
                $paragraph = ImageTextParagraph::find($orderData['id']);
                if ($paragraph) {
                    $paragraph->update(['order' => $orderData['order']]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
