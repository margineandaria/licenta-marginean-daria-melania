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
        'family_id',
        'name',
        'email',
        'password',
        'role',
        'monthly_email_report',
        'education_level',
        'work_domain',
        'geographic_zone',
        'age_category',
        'housing_status', 
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
        ];
    }
     public function hasCompleteProfile()
    {
        return !empty($this->age_category) && 
            !empty($this->work_domain) && 
            !empty($this->education_level) &&
            !empty($this->geographic_zone);
    }
    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'user_id_allocated');
    }
    public function budgets() {
        return $this->hasMany(Budget::class, 'user_id_responsible');
    }
}
