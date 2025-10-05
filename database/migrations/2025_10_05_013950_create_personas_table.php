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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            
            // ペルソナ基本情報
            $table->string('name'); // 相談者役の名前
            $table->integer('age'); // 年齢
            $table->enum('gender', ['male', 'female', 'other']); // 性別
            $table->string('occupation'); // 職業
            $table->integer('career_years'); // 経験年数
            
            // 相談内容・背景
            $table->text('background'); // 背景・現状
            $table->text('concern_category'); // 相談カテゴリ（転職、昇進、人間関係など）
            $table->text('specific_concern'); // 具体的な悩み
            $table->text('desired_outcome'); // 希望する結果
            
            // 性格・特徴
            $table->text('personality_traits'); // 性格特性
            $table->text('communication_style'); // コミュニケーションスタイル
            $table->text('motivation_factors'); // モチベーション要因
            
            // 面談で使用する詳細シナリオ
            $table->text('opening_statement'); // 面談開始時の発言
            $table->text('key_points_to_reveal'); // 面談中に明かすべきポイント
            $table->text('emotional_responses'); // 想定される感情的反応
            $table->text('resistance_points'); // 抵抗しやすいポイント
            
            // 難易度・対象レベル
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']); 
            $table->text('learning_objectives'); // この ペルソナで学べること
            
            // 管理情報
            $table->boolean('is_active')->default(true); // 使用可能フラグ
            $table->text('usage_notes')->nullable(); // 使用上の注意点
            
            $table->timestamps();
            
            // インデックス設定
            $table->index('difficulty_level');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
