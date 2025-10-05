<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'gender',
        'occupation',
        'career_years',
        'background',
        'concern_category',
        'specific_concern',
        'desired_outcome',
        'personality_traits',
        'communication_style',
        'motivation_factors',
        'opening_statement',
        'key_points_to_reveal',
        'emotional_responses',
        'resistance_points',
        'difficulty_level',
        'learning_objectives',
        'usage_notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'age' => 'integer',
        'career_years' => 'integer',
    ];

    // リレーションシップ
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ヘルパーメソッド
    public function getShortDescription()
    {
        return $this->age . '歳・' . $this->occupation . '・' . $this->concern_category;
    }
}
