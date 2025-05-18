<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use App\Models\ImageTextParagraph;
use PhpOffice\PhpWord\Element\Image as PhpWordImage;
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
        $firstTextUsedAsTitle = false;
        $phpwordImagePaths = [];

        foreach ($sections as $section) {
            foreach ($section->getElements() as $element) {
                $text = '';

                // 處理圖片
                if ($element instanceof PhpWordImage) {
                    $imageStream = null;
                    try {
                        $imageStream = $element->getImageStringData(true);
                    } catch (\Throwable $e) {
                        $imageStream = null;
                    }
                    if (!$imageStream) {
                        continue;
                    }
                    $extension = pathinfo($element->getSource(), PATHINFO_EXTENSION) ?: 'jpg';
                    $newImageName = Str::uuid()->toString() . '.' . $extension;
                    $savePath = 'uploads/images/' . $newImageName;
                    Storage::disk('public')->put($savePath, $imageStream);

                    $phpwordImagePaths[] = $savePath;

                    ImageTextParagraph::create([
                        'news_id' => $news->id,
                        'category' => ImageTextParagraph::CATEGORY_IMAGE,
                        'title' => '',
                        'content' => $savePath,
                        'order' => $order,
                        'status' => 1,
                    ]);
                    $order++;
                    continue;
                }

                // 取得文字
                if (method_exists($element, 'getText')) {
                    $text = $element->getText();
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $text = self::processTextRun($element);
                }

                // 跳過空白段落
                if (!is_string($text) || trim($text) === '') {
                    continue;
                }

                // 第一段文字作為新聞標題
                if (!$firstTextUsedAsTitle) {
                    $news->title = $text;
                    $news->save();
                    $firstTextUsedAsTitle = true;
                    continue;
                }

                // 判斷內容類型
                if (filter_var($text, FILTER_VALIDATE_URL)) {
                    if (preg_match('/(youtube\.com|vimeo\.com)/i', $text)) {
                        ImageTextParagraph::create([
                            'news_id' => $news->id,
                            'category' => ImageTextParagraph::CATEGORY_VIDEO,
                            'title' => '',
                            'content' => $text,
                            'order' => $order,
                        ]);
                        $order++;
                        continue;
                    }
                }

                // 一般文字段落直接存
                ImageTextParagraph::create([
                    'news_id' => $news->id,
                    'category' => ImageTextParagraph::CATEGORY_TEXT,
                    'title' => '',
                    'content' => $text,
                    'order' => $order,
                ]);
                $order++;
            }
        }

        // 備援：若 PhpWord 沒有解析到任何圖片，則用進階備援方式抓圖
        if (count($phpwordImagePaths) === 0) {
            $imagesWithPos = self::extractImagesFromDocxWithContext($filePath);
            // 讓 order 從目前最大 order 開始往後排
            $baseOrder = $order;
            foreach ($imagesWithPos as $img) {
                ImageTextParagraph::create([
                    'news_id' => $news->id,
                    'category' => ImageTextParagraph::CATEGORY_IMAGE,
                    'title' => '',
                    'content' => $img['file'],
                    'order' => $baseOrder + $img['position'],
                    'status' => 1,
                ]);
            }
        }
        return $news;
    }

    /**
     * 備援：直接解壓 docx 取得所有圖片
     */
    public static function extractImagesFromDocx($filePath)
    {
        $docxFullPath = storage_path('app/public/' . $filePath);
        $images = [];

        $zip = new \ZipArchive;
        if ($zip->open($docxFullPath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (preg_match('/word\/media\/.+\.(jpg|jpeg|png|gif)$/i', $entry)) {
                    $stream = $zip->getFromIndex($i);
                    $filename = 'uploads/images/' . uniqid() . '_' . basename($entry);
                    Storage::disk('public')->put($filename, $stream);
                    $images[] = $filename;
                }
            }
            $zip->close();
        }

        return $images;
    }

    /**
     * 備援：依照圖片在 docx 內的順序，抓取所有圖片
     */
    public static function extractImagesFromDocxWithContext($filePath)
    {
        $docxFullPath = storage_path('app/public/' . $filePath);
        $zip = new \ZipArchive;
        $imagePositions = [];

        if ($zip->open($docxFullPath) === TRUE) {
            // 讀取 word/document.xml
            $xmlContent = $zip->getFromName('word/document.xml');

            // 抓出所有 <w:drawing> 內的 r:embed (rId)
            preg_match_all('/<w:drawing>.*?<a:blip r:embed="(rId\d+)".*?<\/w:drawing>/s', $xmlContent, $matches);
            $imageIds = $matches[1] ?? [];

            // 讀取 _rels/document.xml.rels 找 rId 對應的圖片檔名
            $relXml = $zip->getFromName('word/_rels/document.xml.rels');
            $relMap = [];
            if ($relXml) {
                preg_match_all('/Id="(rId\d+)"[^>]*Target="media\/([^"]+)"/', $relXml, $relMatches, PREG_SET_ORDER);
                foreach ($relMatches as $rel) {
                    $relMap[$rel[1]] = $rel[2]; // rId => filename.jpg
                }
            }

            foreach ($imageIds as $index => $rId) {
                $mediaFile = $relMap[$rId] ?? null;
                if ($mediaFile) {
                    $imageStream = $zip->getFromName("word/media/" . $mediaFile);
                    $filename = 'uploads/images/' . uniqid() . '_' . $mediaFile;
                    Storage::disk('public')->put($filename, $imageStream);

                    $imagePositions[] = [
                        'file' => $filename,
                        'position' => $index // 這是圖片在 <w:drawing> 出現的順序
                    ];
                }
            }

            $zip->close();
        }

        return $imagePositions; // [{ file: 路徑, position: 3 }, ...]
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
