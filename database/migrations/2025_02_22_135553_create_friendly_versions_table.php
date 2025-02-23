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
        Schema::create('friendly_versions', function (Blueprint $table) {
            $table->id(); // 自動遞增的主鍵欄位，對應到 id
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade'); // 新聞編號, 外來鍵
            $table->string('category'); // 類別
            $table->string('content'); // 內容
            $table->timestamps(); // 自動生成 created_at 和 updated_at 欄位
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendly_versions');
    }
};
