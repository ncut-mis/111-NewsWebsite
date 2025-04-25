<?php

namespace App\Http\Controllers;

use App\Models\ImageTextParagraph;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageTextParagraphsController extends Controller
{
    public function index($newsId)
    {
        $news = News::with('imageTextParagraphs')->findOrFail($newsId);
        return view('staff.reporter.content', compact('news'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_id' => 'required|integer|exists:news,id',
            'category' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'required_if:category,0|string',
            'content_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048', // 加入 webp 格式
            'order' => 'required|integer',
        ]);

        try {
            $imagePath = null;

            if ($validated['category'] == 1 && $request->hasFile('content_file')) {
                $file = $request->file('content_file');
                $imagePath = $file->store('uploads/images', 'public'); // 儲存到 storage/app/public/uploads/images
            }

            $paragraph = ImageTextParagraph::create([
                'news_id' => $validated['news_id'],
                'category' => $validated['category'],
                'title' => $validated['title'],
                'content' => $imagePath ?? $validated['content'], // 儲存圖片路徑或文字內容
                'order' => $validated['order'],
            ]);

            return response()->json([
                'success' => true,
                'id' => $paragraph->id,
                'url' => $imagePath ? asset('storage/' . $imagePath) : null, // 生成完整 URL
            ]);
        } catch (\Exception $e) {
            \Log::error('儲存失敗: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => '儲存失敗: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'content_file' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $paragraph = ImageTextParagraph::findOrFail($id);

            if ($request->hasFile('content_file')) {
                // 刪除舊檔案
                if ($paragraph->content && Storage::disk('public')->exists($paragraph->content)) {
                    Storage::disk('public')->delete($paragraph->content);
                }

                // 儲存新檔案
                $path = $request->file('content_file')->store('uploads/images', 'public');
                $validated['content'] = $path;
            }

            $paragraph->update($validated);

            return response()->json(['success' => true, 'url' => isset($path) ? asset('storage/' . $path) : null]);
        } catch (\Exception $e) {
            \Log::error('更新失敗: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $paragraph = ImageTextParagraph::find($id);
        if ($paragraph) {
            // Delete the file if it exists
            if ($paragraph->category == 1 && $paragraph->content && Storage::disk('public')->exists($paragraph->content)) {
                Storage::disk('public')->delete($paragraph->content);
            }

            $paragraph->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Content not found'], 404);
    }

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
