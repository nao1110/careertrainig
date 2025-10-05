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
        Schema::table('feedback', function (Blueprint $table) {
            $table->integer('communication_rating')->nullable()->comment('コミュニケーション能力（1-5）');
            $table->integer('problem_solving_rating')->nullable()->comment('問題解決力（1-5）');
            $table->integer('self_awareness_rating')->nullable()->comment('自己分析力（1-5）');
            $table->integer('goal_setting_rating')->nullable()->comment('目標設定力（1-5）');
            $table->integer('professionalism_rating')->nullable()->comment('職業意識（1-5）');
            $table->text('communication_feedback')->nullable()->comment('コミュニケーション詳細コメント');
            $table->text('problem_solving_feedback')->nullable()->comment('問題解決詳細コメント');
            $table->text('self_awareness_feedback')->nullable()->comment('自己分析詳細コメント');
            $table->text('goal_setting_feedback')->nullable()->comment('目標設定詳細コメント');
            $table->text('professionalism_feedback')->nullable()->comment('職業意識詳細コメント');
            $table->text('improvement_suggestions')->nullable()->comment('改善提案');
            $table->text('strengths')->nullable()->comment('強み・良い点');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn([
                'communication_rating',
                'problem_solving_rating',
                'self_awareness_rating',
                'goal_setting_rating',
                'professionalism_rating',
                'communication_feedback',
                'problem_solving_feedback',
                'self_awareness_feedback',
                'goal_setting_feedback',
                'professionalism_feedback',
                'improvement_suggestions',
                'strengths'
            ]);
        });
    }
};
