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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // 関連ユーザー
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('consultant_id')->nullable()->constrained('users')->onDelete('set null');
            
            // 面談日時情報
            $table->datetime('appointment_datetime'); // 土日AM9時開始
            $table->integer('duration_minutes')->default(45); // 45分固定
            
            // ステータス管理
            $table->enum('status', [
                'pending',      // 受験者が日時登録、コンサルタント賛同待ち
                'approved',     // コンサルタントが賛同
                'matched',      // 管理者がマッチング確定
                'completed',    // 面談完了
                'cancelled'     // キャンセル
            ])->default('pending');
            
            // Google連携情報
            $table->string('google_meet_url')->nullable();
            $table->string('google_calendar_event_id')->nullable();
            
            // ペルソナ情報
            $table->foreignId('persona_id')->nullable()->constrained('personas')->onDelete('set null');
            
            // 管理メモ
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // インデックス設定
            $table->index(['appointment_datetime', 'status']);
            $table->index('candidate_id');
            $table->index('consultant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
