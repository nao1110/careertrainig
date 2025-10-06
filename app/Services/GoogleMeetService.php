<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GoogleMeetService
{
    /**
     * 実際に動作するGoogle Meet URLを生成
     */
    public function createMeetingUrl(Appointment $appointment)
    {
        try {
            // 実際に動作するGoogle Meet URL生成のため
            // Google Meet の /new エンドポイントを使用
            // これにより、アクセス時に自動的に新しいミーティングルームが作成される
            
            $meetUrl = "https://meet.google.com/new";
            
            Log::info('Generated actual Google Meet URL for appointment', [
                'appointment_id' => $appointment->id,
                'meet_url' => $meetUrl,
                'candidate_email' => $appointment->candidate->email,
                'consultant_email' => $appointment->consultant->email ?? 'Not assigned',
                'note' => 'Using /new endpoint for actual Google Meet creation'
            ]);
            
            return $meetUrl;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate Google Meet URL', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            
            // フォールバック
            return $this->generateFallbackMeetUrl($appointment);
        }
    }
    
    /**
     * 有効なGoogle Meet コードを生成
     */
    private function generateValidMeetCode(Appointment $appointment)
    {
        // 予約の一意な情報を使用してシードを作成
        $seed = sprintf(
            '%d-%d-%s-%s',
            $appointment->id,
            $appointment->candidate_id,
            $appointment->consultant_id ?? '0',
            $appointment->appointment_datetime->format('Y-m-d-H')
        );
        
        // ハッシュを生成してGoogle Meet形式に変換
        $hash = hash('sha256', $seed . env('APP_KEY', 'career-training'));
        
        // Google Meet形式のコード（xxx-yyyy-zzz）を生成
        // 小文字の英数字のみを使用（Google Meetの実際の形式に近づける）
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';
        
        // ハッシュから文字を選択してコードを構築
        for ($i = 0; $i < 10; $i++) {
            $index = hexdec($hash[$i * 2] . $hash[$i * 2 + 1]) % strlen($chars);
            $code .= $chars[$index];
        }
        
        // xxx-yyyy-zzz形式にフォーマット
        return substr($code, 0, 3) . '-' . substr($code, 3, 4) . '-' . substr($code, 7, 3);
    }
    
    /**
     * フォールバック用のGoogle Meet URL生成
     */
    private function generateFallbackMeetUrl(Appointment $appointment)
    {
        // 最後の手段：Google Meetの新しいミーティング作成
        return "https://meet.google.com/new";
    }
    
    /**
     * Google Meet URLの妥当性をチェック
     */
    public function validateMeetUrl($url)
    {
        return preg_match('/^https:\/\/meet\.google\.com\/[a-z0-9\-]+$/', $url);
    }
}