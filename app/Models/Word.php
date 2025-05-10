<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use App\Models\ImageTextParagraph;

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

        foreach ($sections as $section) {
            $elements = $section->getElements();
            $previousText = ''; // 用於存儲上一段文字

            foreach ($elements as $element) {
                $text = '';

                // 檢查元素類型並提取文字
                if (method_exists($element, 'getText')) {
                    $text = $element->getText();
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $childElement) {
                        if (method_exists($childElement, 'getText')) {
                            $text .= $childElement->getText();
                        } elseif ($childElement instanceof \PhpOffice\PhpWord\Element\Image) {
                            // 處理嵌入圖片
                            $imageData = $childElement->getImageStringData();
                            $imageExtension = $childElement->getImageExtension();
                            $imageName = uniqid() . '.' . $imageExtension;
                            $imagePath = 'uploads/images/' . $imageName;

                            // 儲存圖片到指定資料夾
                            Storage::disk('public')->put($imagePath, $imageData);

                            // 新增圖片資料到資料表
                            ImageTextParagraph::create([
                                'news_id' => $news->id,
                                'category' => 1, // 圖片
                                'title' => $previousText, // 使用上一段文字作為標題
                                'content' => $imagePath,
                                'order' => $order++,
                            ]);
                        }
                    }
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Image) {
                    // 處理直接嵌入的圖片
                    $imageData = $element->getImageStringData();
                    $imageExtension = $element->getImageExtension();
                    $imageName = uniqid() . '.' . $imageExtension;
                    $imagePath = 'uploads/images/' . $imageName;

                    // 儲存圖片到指定資料夾
                    Storage::disk('public')->put($imagePath, $imageData);

                    // 新增圖片資料到資料表
                    ImageTextParagraph::create([
                        'news_id' => $news->id,
                        'category' => 1, // 圖片
                        'title' => $previousText, // 使用上一段文字作為標題
                        'content' => $imagePath,
                        'order' => $order++,
                    ]);
                }

                // 確保 $text 是字串且不為空
                if (!is_string($text) || trim($text) === '') {
                    continue;
                }

                // 判斷內容類型
                if (filter_var($text, FILTER_VALIDATE_URL)) {
                    // 判斷為影片（網址）
                    ImageTextParagraph::create([
                        'news_id' => $news->id,
                        'category' => 2, // 影片
                        'title' => $previousText, // 使用上一段文字作為標題
                        'content' => $text,
                        'order' => $order++,
                    ]);
                } elseif (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $text)) {
                    // 判斷為圖片 URL
                    $imageContent = @file_get_contents($text);
                    if ($imageContent !== false) {
                        $imagePath = 'uploads/images/' . basename($text);
                        Storage::disk('public')->put($imagePath, $imageContent);
                        ImageTextParagraph::create([
                            'news_id' => $news->id,
                            'category' => 1, // 圖片
                            'title' => $previousText, // 使用上一段文字作為標題
                            'content' => $imagePath,
                            'order' => $order++,
                        ]);
                    }
                } else {
                    // 判斷為文字
                    ImageTextParagraph::create([
                        'news_id' => $news->id,
                        'category' => 0, // 文字
                        'title' => '',
                        'content' => $text,
                        'order' => $order++,
                    ]);
                }

                // 更新上一段文字
                $previousText = $text;
            }
        }

        return $news;
    }
}
