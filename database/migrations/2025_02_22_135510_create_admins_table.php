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
        Schema::table('admins', function (Blueprint $table) {
            $table->id(); // 主鍵
            $table->string('name'); // 姓名
            $table->string('email')->unique(); // 郵件，設為唯一
            $table->string('password'); // 密碼
            $table->tinyInteger('role'); // 身分 (0: 記者, 1: 主編)
            $table->timestamps(); // created_at 和 updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
        
    }
};
