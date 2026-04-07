<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'user_id',
        'assigned_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedEngineer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('admin', 'manager', 'analyst')) {
            return $query;
        }

        if ($user->hasRole('engineer')) {
            return $query->where(function ($builder) use ($user) {
                $builder->where('assigned_to', $user->id)
                    ->orWhere('user_id', $user->id);
            });
        }

        return $query->where('user_id', $user->id);
    }
}
