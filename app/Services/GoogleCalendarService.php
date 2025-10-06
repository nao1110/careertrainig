<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Calendar_ConferenceSolution;
use Google_Service_Calendar_ConferenceSolutionKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;

class GoogleCalendarService
{
    private $client;
    private $service;

    public function __construct()
    {
        // 設定ファイルパス
        $keyFilePath = env('GOOGLE_SERVICE_ACCOUNT_KEY_PATH', storage_path('app/google-service-account.json'));
        
        // 相対パスの場合はbase_pathを使用
        if (!str_starts_with($keyFilePath, '/')) {
            $keyFilePath = base_path($keyFilePath);
        }
        
        if (!file_exists($keyFilePath)) {
            Log::warning('Google service account key file not found: ' . $keyFilePath);
            $this->service = null;
            return;
        }

        try {
            // Google Clientを初期化
            $this->client = new Google_Client();
            $this->client->setAuthConfig($keyFilePath);
            $this->client->addScope([
                Google_Service_Calendar::CALENDAR,
                Google_Service_Calendar::CALENDAR_EVENTS
            ]);
            
            // サービスアカウントとして認証
            $this->client->useApplicationDefaultCredentials();
            
            $this->service = new Google_Service_Calendar($this->client);
            
            Log::info('Google Calendar service initialized successfully with key file: ' . $keyFilePath);
            
            Log::info('Google Calendar service initialized successfully with key file: ' . $keyFilePath);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Google Calendar service: ' . $e->getMessage());
            Log::error('Key file path: ' . $keyFilePath);
            $this->service = null;
        }
    }

    /**
     * Google Meetリンク付きのカレンダーイベントを作成
     */
    public function createAppointmentEvent(Appointment $appointment)
    {
        // サービスアカウントキーファイルが設定されていない場合は null を返す
        if (!$this->service) {
            Log::info('Google Calendar service not configured, returning null for appointment: ' . $appointment->id);
            return null;
        }
        
        try {
            Log::info('Creating Google Calendar event for appointment: ' . $appointment->id);
            Log::info('Appointment datetime: ' . $appointment->appointment_datetime);
            Log::info('Candidate email: ' . $appointment->candidate->email);
            Log::info('Consultant: ' . ($appointment->consultant ? $appointment->consultant->email : 'Not assigned'));
            
            $service = new Google_Service_Calendar($this->client);
            
            // イベント情報を設定
            $event = new Google_Service_Calendar_Event([
                'summary' => 'キャリアコンサルティング - 面接練習',
                'description' => $this->generateEventDescription($appointment),
                'start' => [
                    'dateTime' => $appointment->appointment_datetime->format('c'),
                    'timeZone' => 'Asia/Tokyo',
                ],
                'end' => [
                    'dateTime' => $appointment->appointment_datetime->copy()->addHour()->format('c'),
                    'timeZone' => 'Asia/Tokyo',
                ],
                // 参加者は後から手動で追加する方式にして、とりあえずGoogle Meet URLを生成する
                // Domain-Wide Delegation なしでも動作するように参加者は除外
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => 'appointment-' . $appointment->id . '-' . time(),
                    ]
                ]
            ]);
            
            Log::info('Creating calendar event with data: ' . json_encode($event->toSimpleObject()));
            
            // イベントを作成
            $calendarId = config('services.google.calendar_id', 'primary');
            $createdEvent = $service->events->insert($calendarId, $event, [
                'conferenceDataVersion' => 1
            ]);
            
            Log::info('Event created successfully: ' . $createdEvent->getId());
            
            // Google Meet URLを返す
            $meetUrl = null;
            if (isset($createdEvent->conferenceData) && 
                isset($createdEvent->conferenceData->entryPoints) &&
                count($createdEvent->conferenceData->entryPoints) > 0) {
                $meetUrl = $createdEvent->conferenceData->entryPoints[0]->uri;
            }
            
            Log::info('Generated Google Meet URL: ' . ($meetUrl ?? 'null'));
            Log::info('Conference data: ' . json_encode($createdEvent->conferenceData));
            
            return $meetUrl;
            
        } catch (Exception $e) {
            Log::error('Google Calendar API error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * イベントの説明文を生成
     */
    private function generateEventDescription($appointment)
    {
        $description = "キャリアコンサルタント面接練習\n\n";
        $description .= "受験者: " . $appointment->candidate->name . "\n";
        
        $consultantName = $appointment->consultant ? $appointment->consultant->name : 
                         (auth()->check() && auth()->user()->isConsultant() ? auth()->user()->name : 'コンサルタント未定');
        $description .= "コンサルタント: " . $consultantName . "\n";
        
        if ($appointment->persona) {
            $description .= "ペルソナ: " . $appointment->persona->name . "\n";
        }
        
        $description .= "セッション時間: 45分\n";
        $description .= "種類: 面接練習\n";
        
        $description .= "\nシステム: キャリトレ・モーニング";
        
        return $description;
    }

    /**
     * 一意のMeetingコードを生成
     */
    private function generateMeetingCode($appointmentId)
    {
        // 一意性を保つためのコード生成
        $code = 'mkt-' . substr(md5('appointment-' . $appointmentId . '-' . time()), 0, 8);
        return $code;
    }

    /**
     * フォールバック用のMeet URLを生成（API失敗時）
     */
    private function generateFallbackMeetUrl($appointment)
    {
        // 簡易的なGoogle Meet URL生成（実際のAPIが使えない場合）
        $meetingId = 'career-' . $appointment->id . '-' . time();
        return "https://meet.google.com/" . substr(str_replace('-', '', $meetingId), 0, 10);
    }

    /**
     * カレンダーイベントを削除
     */
    public function deleteEvent($eventId)
    {
        if (!$this->service || !$eventId) {
            return false;
        }

        try {
            $this->service->events->delete('primary', $eventId);
            Log::info('Google Calendar event deleted', ['event_id' => $eventId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete Google Calendar event', [
                'event_id' => $eventId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * カレンダーイベントを更新
     */
    public function updateEvent($eventId, $appointment)
    {
        if (!$this->service || !$eventId) {
            return false;
        }

        try {
            // 既存イベントを取得
            $event = $this->service->events->get('primary', $eventId);
            
            // 時刻を更新
            $startDateTime = Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->start_time->format('H:i:s'));
            $endDateTime = $startDateTime->copy()->addMinutes($appointment->duration_minutes ?? 60);
            
            $event->setStart([
                'dateTime' => $startDateTime->toISOString(),
                'timeZone' => 'Asia/Tokyo',
            ]);
            
            $event->setEnd([
                'dateTime' => $endDateTime->toISOString(),
                'timeZone' => 'Asia/Tokyo',
            ]);
            
            $event->setDescription($this->generateEventDescription($appointment));
            
            // イベントを更新
            $updatedEvent = $this->service->events->update('primary', $eventId, $event);
            
            Log::info('Google Calendar event updated', [
                'appointment_id' => $appointment->id,
                'event_id' => $eventId
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update Google Calendar event', [
                'event_id' => $eventId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
