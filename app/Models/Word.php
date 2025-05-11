<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use App\Models\ImageTextParagraph;
use PhpOffice\PhpWord\Element\Image;
use Illuminate\Support\Str;

class Word extends Model
{
    use HasFactory;

    public static function processWordFile($file, $categoryId)
    {
        // 儲存檔案
        $filePath = $file->store('uploads/word', 'public');
        $fileName = $file->getClientOriginalName();

        // 創建 news 資料
        $news = News::create([
            'title' => pathinfo($fileName, PATHINFO_FILENAME),
            'category_id' => $categoryId,
            'reporter_id' => 1,
            'editor_id' => 1,
            'status' => 0,
            'web_version' => '',
            'word_version' => $filePath,
        ]);

        // 解析 Word 檔案
        $phpWord = IOFactory::load(storage_path('app/public/' . $filePath));
        $sections = $phpWord->getSections();
        $order = 1;
        $pendingMedia = null; // 用於存儲待處理的圖片或影片

        foreach ($sections as $section) {
            foreach ($section->getElements() as $element) {
                $text = '';

                // 處理內嵌圖片
                if ($element instanceof Image) {
                    $imageTempPath = $element->getSource(); // 獲取圖片的暫存路徑

                    if (file_exists($imageTempPath)) {
                        $imageContent = file_get_contents($imageTempPath); // 讀取圖片內容

                        // 生成唯一名稱避免重複
                        $newImageName = Str::uuid()->toString() . '.' . pathinfo($imageTempPath, PATHINFO_EXTENSION);
                        $savePath = 'uploads/images/' . $newImageName;

                        // 儲存圖片到 public disk
                        Storage::disk('public')->put($savePath, $imageContent);

                        // 延遲設置標題
                        $pendingMedia = [
                            'news_id' => $news->id,
                            'category' => ImageTextParagraph::CATEGORY_IMAGE, // 圖片類別
                            'title' => '', // 暫時不設置標題
                            'content' => $savePath,
                            'order' => $order++,
                        ];
                    }
                    continue; // 略過其他處理
                }

                // 檢查元素類型並提取文字
                if (method_exists($element, 'getText')) {
                    $text = $element->getText();
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $text = self::processTextRun($element);
                }

                // 確保 $text 是字串且不為空
                if (!is_string($text) || trim($text) === '') {
                    continue;
                }

                // 如果有待處理的圖片或影片，使用當前文字作為其標題
                if ($pendingMedia) {
                    $pendingMedia['title'] = $text; // 設置標題
                    ImageTextParagraph::create($pendingMedia); // 儲存到資料庫
                    $pendingMedia = null; // 清空待處理的媒體
                    continue; // 略過當前文字段落的處理
                }

                // 判斷內容類型
                if (filter_var($text, FILTER_VALIDATE_URL)) {
                    if (preg_match('/(youtube\.com|vimeo\.com)/i', $text)) {
                        // 儲存影片段落，延遲設置標題
                        $pendingMedia = [
                            'news_id' => $news->id,
                            'category' => ImageTextParagraph::CATEGORY_VIDEO, // 影片類別
                            'title' => '', // 暫時不設置標題
                            'content' => $text,
                            'order' => $order++,
                        ];
                    }
                } else {
                    // 儲存文字段落
                    ImageTextParagraph::create([
                        'news_id' => $news->id,
                        'category' => ImageTextParagraph::CATEGORY_TEXT, // 文字類別
                        'title' => '',
                        'content' => $text,
                        'order' => $order++,
                    ]);
                }
            }

            // 如果最後一個元素是圖片或影片，且沒有後續文字作為標題，則使用空標題儲存
            if ($pendingMedia) {
                ImageTextParagraph::create($pendingMedia);
            }
        }

        return $news;
    }

    private static function processTextRun($textRun)
    {
        $text = '';
        foreach ($textRun->getElements() as $childElement) {
            if (method_exists($childElement, 'getText')) {
                $text .= $childElement->getText();
            }
        }
        return $text;
    }
}
