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
            // アクティブフラグを追加
            $table->boolean('is_active')->default(true);
            
            // プロフィール関連フィールドを追加
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('certification_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'phone',
                'bio',
                'experience_years',
                'certification_number'
            ]);
        });
    }
};
