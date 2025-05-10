<?php

namespace App\Http\Controllers;

use App\Models\Category; 
use App\Models\Word; // 正確引入 Word 模型
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WordController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // 獲取所有類別
        return view('staff.reporter.word', compact('categories')); // 傳遞 $categories 到視圖
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:doc,docx', // 確保上傳檔案為 Word 格式
            'category' => 'required|exists:categories,id', // 確保類別存在於資料庫
        ]);

        try {
            $news = Word::processWordFile($request->file('file'), $request->category); // 正確使用 processWordFile 方法

            return Redirect::route('staff.reporter.news.index')
                ->with('success', '新聞已成功上傳並解析！');
        } catch (\Exception $e) {
            // 捕獲例外並返回錯誤訊息
            return Redirect::back()
                ->with('error', '上傳失敗，請檢查檔案內容或聯繫管理員。');
        }
    }
}
