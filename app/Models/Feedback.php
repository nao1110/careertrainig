<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'consultant_id',
        'candidate_id',
        'overall_rating',
        'listening_skills',
        'questioning_skills',
        'empathy_skills',
        'goal_setting_skills',
        'solution_skills',
        'strengths',
        'improvements',
        'specific_advice',
        'consultant_comments',
        'exam_tips',
        'recommended_for_exam'
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'listening_skills' => 'integer',
        'questioning_skills' => 'integer',
        'empathy_skills' => 'integer',
        'goal_setting_skills' => 'integer',
        'solution_skills' => 'integer',
        'recommended_for_exam' => 'boolean',
    ];

    // リレーションシップ
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    // ヘルパーメソッド
    public function getAverageRating()
    {
        $ratings = [
            $this->listening_skills,
            $this->questioning_skills,
            $this->empathy_skills,
            $this->goal_setting_skills,
            $this->solution_skills
        ];
        
        $validRatings = array_filter($ratings, function($rating) {
            return !is_null($rating);
        });
        
        return count($validRatings) > 0 ? round(array_sum($validRatings) / count($validRatings), 1) : 0;
    }

    public function getDetailedRatings()
    {
        return [
            '傾聴力' => $this->listening_skills,
            '質問力' => $this->questioning_skills,
            '共感力' => $this->empathy_skills,
            '目標設定力' => $this->goal_setting_skills,
            '課題解決力' => $this->solution_skills
        ];
    }
}
