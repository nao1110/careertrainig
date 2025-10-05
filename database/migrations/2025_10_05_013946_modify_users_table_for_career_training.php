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
        Schema::table('users', function (Blueprint $table) {
            // Google OAuth関連フィールド
            $table->string('google_id')->unique()->nullable();
            $table->text('google_avatar')->nullable();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            
            // ユーザータイプ (candidate: 受験者, consultant: コンサルタント)
            $table->enum('user_type', ['candidate', 'consultant'])->default('candidate');
            
            // キャリアコンサルタント資格情報 (コンサルタントのみ)
            $table->string('qualification_number')->nullable();
            $table->date('qualification_date')->nullable();
            $table->text('profile_bio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'google_avatar',
                'google_token',
                'google_refresh_token',
                'user_type',
                'qualification_number',
                'qualification_date',
                'profile_bio'
            ]);
        });
    }
};
