<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'google_id',
        'google_avatar',
        'google_token',
        'google_refresh_token',
        'qualification_number',
        'qualification_date',
        'profile_bio',
        'phone',
        'bio',
        'experience_years',
        'certification_number',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'qualification_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // リレーションシップ
    public function candidateAppointments()
    {
        return $this->hasMany(Appointment::class, 'candidate_id');
    }

    public function consultantAppointments()
    {
        return $this->hasMany(Appointment::class, 'consultant_id');
    }

    public function feedbackGiven()
    {
        return $this->hasMany(Feedback::class, 'consultant_id');
    }

    public function feedbackReceived()
    {
        return $this->hasMany(Feedback::class, 'candidate_id');
    }

    // ヘルパーメソッド
    public function isCandidate()
    {
        return $this->user_type === 'candidate';
    }

    public function isConsultant()
    {
        return $this->user_type === 'consultant';
    }
}
