<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id(); // 自動遞增的主鍵欄位，對應到 id
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // 類別編號, 外來鍵
            $table->foreignId('reporter_id')->constrained('reporters')->onDelete('cascade'); // 記者編號, 外來鍵
            $table->foreignId('editor_id')->constrained('editors')->onDelete('cascade'); // 主編編號, 外來鍵
            $table->string('title'); // 標題
            $table->integer('status')->default(0); // 狀態，預設值為 0
            $table->string('web_version'); // 網頁版本
            $table->string('word_version'); // word版本
            $table->timestamps(); // 會自動生成 created_at 和 updated_at 欄位
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
