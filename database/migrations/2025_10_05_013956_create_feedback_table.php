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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            
            // 関連情報
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('consultant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            
            // 評価項目（チェック形式）
            $table->integer('listening_skills')->nullable(); // 1-5点 傾聴力
            $table->integer('questioning_skills')->nullable(); // 1-5点 質問力  
            $table->integer('empathy_skills')->nullable(); // 1-5点 共感力
            $table->integer('goal_setting_skills')->nullable(); // 1-5点 目標設定力
            $table->integer('solution_skills')->nullable(); // 1-5点 課題解決力
            $table->integer('overall_rating')->nullable(); // 1-5点 総合評価
            
            // フリー記述欄
            $table->text('strengths')->nullable(); // 良かった点
            $table->text('improvements')->nullable(); // 改善点
            $table->text('specific_advice')->nullable(); // 具体的なアドバイス
            $table->text('consultant_comments')->nullable(); // コンサルタント自由記述
            
            // 試験対策関連
            $table->text('exam_tips')->nullable(); // 試験対策アドバイス
            $table->boolean('recommended_for_exam')->default(false); // 試験受験推奨レベル
            
            $table->timestamps();
            
            // インデックス設定
            $table->index('appointment_id');
            $table->index('candidate_id');
            $table->index('consultant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
