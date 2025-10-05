<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'consultant_id',
        'appointment_datetime',
        'duration_minutes',
        'status',
        'google_meet_url',
        'google_calendar_event_id',
        'persona_id',
        'consultation_topic',
        'special_requests',
        'notes'
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    // リレーションシップ
    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    // ヘルパーメソッド
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isMatched()
    {
        return $this->status === 'matched';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function getEndDateTime()
    {
        return $this->appointment_datetime->addMinutes($this->duration_minutes);
    }

    // 土日AM9時制約チェック
    public function isValidWeekendMorningTime()
    {
        // 第30回試験用の特定日程をチェック
        $examDates = [
            '2025-10-05', '2025-10-06', '2025-10-12', '2025-10-13', 
            '2025-10-19', '2025-10-20', '2025-10-26', '2025-10-27',
            '2025-11-08', '2025-11-09', '2025-11-14', '2025-11-15', 
            '2025-11-16', '2025-11-22', '2025-11-23'
        ];
        
        $datetime = $this->appointment_datetime;
        $dateOnly = $datetime->format('Y-m-d');
        return in_array($dateOnly, $examDates) && $datetime->hour === 9 && $datetime->minute === 0;
    }
}
