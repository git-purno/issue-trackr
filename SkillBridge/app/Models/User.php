<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'locale',
        'password',
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
            'phone_verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class);
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(JobPost::class, 'client_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'freelancer_id');
    }

    public function gigs(): HasMany
    {
        return $this->hasMany(Gig::class);
    }

    public function trustBadge(): string
    {
        return match (true) {
            $this->trust_score >= 80 => 'Verified',
            $this->trust_score >= 60 => 'Gold',
            $this->trust_score >= 40 => 'Silver',
            default => 'Bronze',
        };
    }

    public function recalculateTrustScore(): void
    {
        $score = 0;
        $score += $this->email_verified_at ? 10 : 0;
        $score += $this->phone_verified_at ? 20 : 0;
        $score += $this->verifications()->where('type', 'student')->where('status', 'approved')->exists() ? 30 : 0;
        $score += $this->verifications()->where('type', 'nid')->where('status', 'approved')->exists() ? 20 : 0;
        $score += min(10, $this->postedJobs()->where('status', 'completed')->count() + $this->proposals()->where('status', 'accepted')->count());

        $ratingBoost = (int) round(Review::where('reviewee_id', $this->id)->avg('stars') ?? 0);
        $this->forceFill(['trust_score' => min(100, $score + $ratingBoost)])->save();
    }

    public function recalculateProfileCompletion(): void
    {
        $profile = $this->profile;
        $checks = [
            filled($this->name),
            filled($this->email),
            filled($this->phone),
            filled($profile?->bio),
            filled($profile?->skills),
            filled($profile?->portfolio_links),
            filled($profile?->university),
            filled($profile?->department),
        ];

        $this->forceFill([
            'profile_completion_score' => (int) round((count(array_filter($checks)) / count($checks)) * 100),
        ])->save();
    }
}
