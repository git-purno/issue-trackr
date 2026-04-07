<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'justification',
        'risk_analysis',
        'affected_systems',
        'impact_level',
        'status',
        'user_id',
        'scheduled_at',
        'rollback_plan',
        'verified',
        'analyst_id',
        'analyst_approved_at',
        'manager_id',
        'manager_approved_at',
        'admin_id',
        'admin_approved_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'verified' => 'boolean',
            'analyst_approved_at' => 'datetime',
            'manager_approved_at' => 'datetime',
            'admin_approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('admin', 'manager', 'analyst')) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}
